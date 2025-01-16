<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projet;
use App\Models\Competence;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjetController extends Controller
{
    public function index()
    {
        return Projet::paginate(10); // Inclut les tâches associées
        // return Projet::with('taches')->paginate(10); // Inclut les tâches associées
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'nom_projet' => 'required|string|max:255',
            'description_projet' => 'required|string',
            'date_deb' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_deb',
            'type_projet' => 'required|string|max:50',
        ]);

        // Set 'etat_projet' to 'A démarrer' by default
        $validated['etat_projet'] = 'A démarrer';

        // Create a new project with the validated data
        $projet = Projet::create($validated);

        // Return the created project in the response
        return response()->json($projet, 201);
    }

    public function update(Request $request, $id)
    {
        // Valider les données de la requête
        $validated = $request->validate([
            'nom_projet' => 'string|max:255',
            'description_projet' => 'string',
            'date_deb' => 'date',
            'date_fin' => 'date|after_or_equal:date_deb',
            'etat_projet' => 'string|max:50',
            'type_projet' => 'string|max:50',
            'taux_avancement' => 'required|integer|min:0|max:100',
            'date_glissement' => 'required_if:etat_projet,Glissement|date|nullable',
            'cause_glissement' => 'required_if:etat_projet,Glissement|string|nullable',
        ]);

        // Trouver le projet avec l'ID donné
        $projet = Projet::find($id);

        // Vérifier si le projet existe
        if (!$projet) {
            return response()->json(['message' => 'Projet non trouvé'], 404);
        }

        // Vérifier si le projet est déjà terminé
        if ($projet->etat_projet === 'Terminé') {
            return response()->json(['message' => 'Impossible de modifier un projet terminé'], 403);
        }

        // Empêcher le retour à "A démarrer" si le projet a déjà démarré
        if ($validated['etat_projet'] === 'A démarrer' && $projet->etat_projet !== 'A démarrer') {
            return response()->json(['message' => 'Impossible de revenir à l\'état "A démarrer"'], 422);
        }

        // Gérer la cohérence entre l'état et le taux d'avancement
        if (isset($validated['etat_projet'])) {
            if ($validated['etat_projet'] === 'Terminé') {
                $validated['taux_avancement'] = 100;
            } elseif ($validated['etat_projet'] === 'A démarrer') {
                $validated['taux_avancement'] = 0;
            } elseif ($validated['taux_avancement'] === 0 && $projet->etat_projet !== 'A démarrer') {
                // Empêcher le retour à 0% si le projet n'est pas "A démarrer"
                return response()->json(['message' => 'Le taux d\'avancement ne peut pas être 0% une fois le projet démarré'], 422);
            } elseif ($validated['taux_avancement'] === 100) {
                // Forcer l'état à "Terminé" si 100%
                $validated['etat_projet'] = 'Terminé';
            }

            // // Réinitialiser les champs de glissement si l'état n'est pas "Glissement"
            // if ($validated['etat_projet'] !== 'Glissement') {
            //     $validated['date_glissement'] = null;
            //     $validated['cause_glissement'] = null;
            // }
        }

        // Mettre à jour le projet avec les données validées
        $projet->update($validated);

        // Retourner la réponse avec le projet mis à jour
        return response()->json($projet);
    }    

    public function show($id)
    {
        // Trouver le projet avec l'ID donné
        $projet = Projet::find($id);

        // Vérifier si le projet existe
        if (!$projet) {
            return response()->json(['message' => 'Projet non trouvé'], 404);
        }

        // Retourner le projet sous forme de réponse JSON
        return response()->json($projet);
    }

    public function getProjetCompetences($projetId)
    {
        $projet = Projet::with('competences')->findOrFail($projetId);
        return response()->json($projet->competences);
    }

    public function assignCompetenceToProjet($projetId, $competenceId)
    {
        $projet = Projet::findOrFail($projetId);
        $projet->competences()->syncWithoutDetaching($competenceId);

        return response()->json([
            'message' => 'Compétence assignée avec succès au projet.',
        ], 201);
    }

    public function removeCompetenceFromProjet($projetId, $competenceId)
    {
        $projet = Projet::findOrFail($projetId);
        $projet->competences()->detach($competenceId);

        return response()->json([
            'message' => 'Compétence retirée avec succès du projet.',
        ], 200);
    }

    /**
     * Récupère les utilisateurs éligibles pour le projet
     */
    public function getEligibleUsers($projetId)
    {
        try {
            $projet = Projet::with('competences')->findOrFail($projetId);
            
            // Récupérer les IDs des compétences du projet
            $projetCompetenceIds = $projet->competences->pluck('id');
            
            // Si le projet n'a pas de compétences, retourner un tableau vide
            if ($projetCompetenceIds->isEmpty()) {
                return response()->json([]);
            }

            // Récupérer les utilisateurs qui ont au moins une des compétences requises
            $eligibleUsers = User::whereHas('competences', function ($query) use ($projetCompetenceIds) {
                $query->whereIn('competences.id', $projetCompetenceIds);
            })
            ->whereDoesntHave('projets', function ($query) use ($projetId) {
                $query->where('projets.id', $projetId);
            })
            ->whereHas('role', function($query) {
                // Exclure les rôles admin et manager
                // et trier par hiérarchie de rôle
                $query->whereNotIn('lib_role', ['Admin', 'Manager'])
                      ->orderByRaw("CASE 
                          WHEN lib_role = 'Chef de projet' THEN 1
                          WHEN lib_role = 'Développeur senior' THEN 2
                          WHEN lib_role = 'Développeur' THEN 3
                          WHEN lib_role = 'Ingénieur de test' THEN 4
                          ELSE 5 END");
            })
            ->with(['role', 'competences' => function ($query) use ($projetCompetenceIds) {
                $query->whereIn('competences.id', $projetCompetenceIds);
            }])
            ->orderBy('nom_user')
            ->get();

            return response()->json($eligibleUsers);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des utilisateurs éligibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère les utilisateurs assignés au projet
     */
    public function getProjetUsers($projetId)
    {
        try {
            $users = User::whereHas('projets', function($query) use ($projetId) {
                $query->where('projets.id', $projetId);
            })
            ->with(['role', 'competences'])
            ->orderByRaw("CASE 
                WHEN roles.lib_role = 'Chef de projet' THEN 1
                WHEN roles.lib_role = 'Développeur senior' THEN 2
                WHEN roles.lib_role = 'Développeur' THEN 3
                WHEN roles.lib_role = 'Ingénieur de test' THEN 4
                ELSE 5 END")
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*')
            ->paginate(5);

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des utilisateurs du projet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assigne un utilisateur au projet
     */
    public function assignUserToProjet(Request $request, $projetId, $userId)
    {
        try {
            DB::beginTransaction();

            $projet = Projet::with('competences')->findOrFail($projetId);
            $user = User::with('competences')->findOrFail($userId);

            // Vérifier si l'utilisateur a au moins une compétence requise
            $userCompetenceIds = $user->competences->pluck('id');
            $projetCompetenceIds = $projet->competences->pluck('id');
            
            $hasRequiredSkill = $userCompetenceIds->intersect($projetCompetenceIds)->isNotEmpty();

            if (!$hasRequiredSkill) {
                return response()->json([
                    'message' => 'L\'utilisateur ne possède aucune des compétences requises pour ce projet.'
                ], 400);
            }

            // Vérifier si l'utilisateur n'est pas déjà assigné au projet
            if ($projet->users()->where('users.id', $userId)->exists()) {
                return response()->json([
                    'message' => 'L\'utilisateur est déjà assigné à ce projet.'
                ], 400);
            }

            $projet->users()->attach($userId, [
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Utilisateur assigné avec succès au projet.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de l\'assignation de l\'utilisateur au projet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retire un utilisateur du projet
     */
    public function removeUserFromProjet($projetId, $userId)
    {
        try {
            $projet = Projet::findOrFail($projetId);
            
            if (!$projet->users()->where('users.id', $userId)->exists()) {
                return response()->json([
                    'message' => 'L\'utilisateur n\'est pas assigné à ce projet.'
                ], 400);
            }

            $projet->users()->detach($userId);

            return response()->json([
                'message' => 'Utilisateur retiré avec succès du projet.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du retrait de l\'utilisateur du projet',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
