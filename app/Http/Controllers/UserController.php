<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use App\Models\Competence;
use App\Models\Projet;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $fields = request()->validate(
            [
                'email' => ['required', 'email', 'exists:users,email'],
                'password' => ['required'],
            ],
            [
                'email.exists' => 'L\'e-mail sélectionné n\'est pas valide ou le compte a été désactivé.'
            ]
        );
        $credentials = [
            'email' => $fields['email'],
            'password' => $fields['password'],
            // 'active_usr' => 1
        ];

        $result = Auth::attempt($credentials);

        if (!$result) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // $userInfos = Auth::user()->with('role');
        $user = User::where('email', $fields['email'])->with('role')->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        auth()->user()->tokens()->delete();
        return response()->json('Done');
    }

    public function index()
    {
        return User::with('role')->paginate(10); // Limite à 10 utilisateurs par page
    }   

    public function show($id)
    {
        $users = User::where('users.id', $id)
                     ->join('roles', 'users.role_id', '=', 'roles.id')
                     ->select('users.*', 'roles.lib_role')
                     ->first();
    
        return response()->json($users);
    }    

    public function store(Request $request)
    {
        // Validation des données entrantes
        $validated = $request->validate([
            'nom_user' => 'required|string|max:255',
            'prenom_user' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'nom_user' => $validated['nom_user'],
            'prenom_user' => $validated['prenom_user'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
            'statut' => 'Actif',
        ]);

        // Retourner une réponse (par exemple, un JSON ou une redirection)
        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'user' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Récupérer l'utilisateur ou renvoyer une erreur 404
        $user = User::findOrFail($id);

        // Valider les données de la requête
        $validated = $request->validate([
            'nom_user' => 'required|string|max:255', // Nom obligatoire
            'prenom_user' => 'required|string|max:255', // Prénom obligatoire
            'email' => "required|email|unique:users,email,$user->id,id", // Email unique sauf pour cet utilisateur
            'role_id' => 'required|exists:roles,id', // Role valide
            'statut' => 'required', // Status obligatoire
        ]);
    
        // Mise à jour des données de l'utilisateur
        $user->update($validated);
    
        // Retourner une réponse (par exemple, un JSON ou une redirection)
        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $user,
        ], 201);
    }
    
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 201);
    }

    public function getUsersByRole($role)
    {
        // Récupérer l'ID du rôle en fonction du libellé
        $roleRecord = Role::where('lib_role', $role)->first();
    
        // Vérifier si le rôle existe
        if (!$roleRecord) {
            return response()->json(['message' => 'Role not found'], 404);
        }
    
        // Récupérer les utilisateurs par rôle avec pagination
        $users = User::where('role_id', $roleRecord->id)->with('role')->paginate(5);
    
        return response()->json($users);
    }

    public function assignBackup(Request $request)
    {
        // Validation des données de la requête
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // L'utilisateur doit exister
            'backup_id' => 'required|exists:users,id|different:user_id', // Le backup doit exister et être différent de l'utilisateur
        ]);
    
        // Récupérer les deux utilisateurs
        $user = User::findOrFail($validated['user_id']);
        $backupUser = User::findOrFail($validated['backup_id']);
    
        // Assigner les backups mutuels
        $user->assignMutualBackup($backupUser);
        $backupUser->assignMutualBackup($user); // Assigner l'utilisateur comme backup de l'utilisateur de backup
    
        // Réponse JSON
        return response()->json(['message' => 'Backup attribué avec succès.', 'backup' => $backupUser], 201);
    }
    
    // public function getBackupPotentiels(User $resource)
    // {
    //     // Récupérer les compétences de la ressource
    //     $competencesRessource = $resource->competences;

    //     // Récupérer les utilisateurs ayant des compétences communes et le même rôle
    //     $backupPotentiels = User::whereHas('competences', function ($query) use ($competencesRessource) {
    //         $query->whereIn('competences.id', $competencesRessource->pluck('id'));
    //     })
    //     ->where('id', '!=', $resource->id) // Exclure l'utilisateur lui-même
    //     ->where('role_id', $resource->role_id) // Même rôle que l'utilisateur
    //     ->whereDoesntHave('role', function($query) {
    //         $query->where('lib_role', 'admin'); // Exclure les admins
    //     })
    //     ->with('role') // Charger la relation role
    //     ->get();

    //     return response()->json($backupPotentiels, 200); // Correction du return et code 200 au lieu de 201
    // }

    public function getBackupPotentiels($userId)
    {
        $user = User::findOrFail($userId);
        
        // Récupérer les compétences de la ressource
        $competencesRessource = $user->competences->pluck('id');

        // Vérifier si l'utilisateur possède des compétences
        if ($competencesRessource->isEmpty()) {
            return response()->json(['message' => 'Aucune compétence associée à cet utilisateur.'], 404);
        }

        // Récupérer les utilisateurs ayant des compétences communes et le même rôle
        $backupPotentiels = User::whereHas('competences', function ($query) use ($competencesRessource) {
                $query->whereIn('competences.id', $competencesRessource);
            })
            ->where('id', '!=', $user->id) // Exclure l'utilisateur lui-même
            ->where('role_id', $user->role_id) // Même rôle que l'utilisateur
            ->whereDoesntHave('role', function ($query) {
                $query->where('lib_role', 'admin'); // Exclure les utilisateurs avec le rôle "admin"
            })
            ->with(['role', 'competences']) // Charger les relations nécessaires
            ->get();

        // Vérifier si des backups potentiels existent
        if ($backupPotentiels->isEmpty()) {
            return response()->json(['message' => 'Aucun backup potentiel trouvé.'], 404);
        }

        // Retourner les utilisateurs trouvés
        return response()->json($backupPotentiels, 200);
    }

    public function assignBackupToUser(Request $request, $userId, $backupId)
    {
        $user = User::findOrFail($userId);
        $backup = User::findOrFail($backupId);

        // Vérifier si le backup est valide (même rôle, pas admin)
        if ($backup->role_id !== $user->role_id || $backup->role->lib_role === 'admin') {
            return response()->json(['message' => 'Utilisateur non éligible comme backup.'], 422);
        }

        // Vérifier si un backup existe déjà pour cet utilisateur
        $existingBackup = DB::table('backups')
            ->where('user_id', $user->id)
            ->first();

        if ($existingBackup) {
            // Mettre à jour l'entrée existante
            DB::table('backups')
                ->where('id', $existingBackup->id)
                ->update(['backup_id' => $backup->id, 'updated_at' => now()]);
        } else {
            // Créer une nouvelle entrée dans la table `backup`
            DB::table('backups')->insert([
                'user_id' => $user->id,
                'backup_id' => $backup->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Backup assigné avec succès.',
            'backup' => $backup,
        ], 200);
    }

    public function removeBackupToUser($userId)
    {
        $user = User::findOrFail($userId);
    
        // Récupérer le backup assigné depuis la table `backups`
        $backup = DB::table('backups')
            ->where('user_id', $user->id)
            ->first();
    
        // Vérifier si un backup est assigné
        if (!$backup) {
            return response()->json(['message' => 'Aucun backup assigné à cet utilisateur.'], 404);
        }
    
        // Supprimer le backup
        DB::table('backups')
            ->where('user_id', $user->id)
            ->delete(); // Suppression de l'entrée de backup

        return response()->json(['message' => 'Backup supprimé avec succès.'], 200);
    }    

    public function getAssignedBackup($userId)
    {
        // Vérifier si l'utilisateur existe
        $user = User::findOrFail($userId);

        // Récupérer le backup assigné depuis la table `backups`
        $backup = DB::table('backups')
            ->where('user_id', $user->id)
            ->join('users', 'backups.backup_id', '=', 'users.id') // Joindre avec la table `users` pour obtenir les détails du backup
            ->first();

        // Vérifier si un backup est assigné
        if (!$backup) {
            return response()->json(['message' => 'Aucun backup assigné à cet utilisateur.'], 404);
        }

        // Retourner les informations du backup
        return response()->json($backup, 200);
    }

    public function assignCompetenceToUser($user, $skill)
    {
        // Assigner la compétence à l'utilisateur
        $user = User::findOrFail($user);
        $user->competences()->syncWithoutDetaching($skill);

        return response()->json([
            'message' => 'Compétence assignée avec succès à l\'utilisateur.',
        ], 201);
    }

    public function getUserCompetences($userId)
    {
        $user = User::with('competences')->findOrFail($userId);

        return response()->json($user->competences);
    }

    public function removeCompetenceToUser($userId, $competenceId)
    {
        $user = user::findOrFail($userId);
        $user->competences()->detach($competenceId);

        return response()->json([
            'message' => 'Compétence retirée avec succès à l\'utilisateur.',
        ], 200);
    }

    /**
     * Récupère les projets où l'utilisateur est affecté avec ses tâches associées
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProjetsAffectes($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Récupérer les projets où l'utilisateur est affecté
            $projets = $user->projets()->paginate(5);

            return response()->json($projets);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des projets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère toutes les tâches assignées à un utilisateur
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksForRessource($id)
    {
        try {
            $user = User::findOrFail($id);
            
            $taches = $user->tasks()
                ->with('projet')
                ->paginate(5);

            return response()->json($taches);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des tâches',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableProjects(User $user)
    {
        try {
            // Récupérer les IDs des compétences de l'utilisateur
            $userCompetenceIds = $user->competences->pluck('id');
            
            // Si l'utilisateur n'a pas de compétences, retourner un tableau vide
            if ($userCompetenceIds->isEmpty()) {
                return response()->json([]);
            }

            // Récupérer les projets qui ont au moins une des compétences de l'utilisateur
            // et auxquels l'utilisateur n'est pas déjà assigné
            $availableProjects = Projet::whereHas('competences', function ($query) use ($userCompetenceIds) {
                $query->whereIn('competences.id', $userCompetenceIds);
            })
            ->whereNotIn('id', $user->projets->pluck('id'))
            ->with(['competences' => function ($query) use ($userCompetenceIds) {
                $query->whereIn('competences.id', $userCompetenceIds);
            }])
            ->orderBy('nom_projet')
            ->get();

            return response()->json($availableProjects);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des projets disponibles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function assignProject(Request $request, $userId, $projetId)
    {
        try {
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
                'message' => 'Erreur lors de l\'assignation du projet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeProject(Request $request, $userId, $projetId)
    {
        $user = User::findOrFail($userId);
        $user->projets()->detach($projetId);
        return response()->json(['message' => 'Projet retiré avec succès']);
    }

    
}
