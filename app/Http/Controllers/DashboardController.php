<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardData(Request $request)
    {
        $user = auth()->user()->load('role'); // Récupérer l'utilisateur connecté

        // Récupérer les données générales
        $totalProjects = Projet::count();
        $totalTasksInProgress = Projet::where('etat_projet', 'En cours')->count(); // Ajustez selon votre logique
        $totalUsers = User::count();
        $totalTasksFinish = Projet::where('etat_projet', 'Terminé')->count();

        // Récupérer les 5 derniers projets
        if ($user->role->lib_role === 'Administrateur' || $user->role->lib_role === 'Manager') {
            // Si l'utilisateur est Administrateur ou Manager, récupérer tous les projets
            $lastProjects = Projet::orderBy('created_at', 'desc')->take(5)->get();
        } else {
            // Sinon, récupérer uniquement les projets affectés à l'utilisateur via la table pivot
            $lastProjects = $user->projets()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        // Récupérer la meilleure ressource (utilisateur ayant le plus de tâches terminées)
        $bestResource = User::withCount(['tasks as completed_tasks_count' => function ($query) {
            $query->where('statut', 'terminé'); // Ajustez selon votre logique de statut
        }])
            ->with('role')
            ->orderBy('completed_tasks_count', 'desc')
            ->first();

        // Retourner les données sous forme de réponse JSON
        return response()->json([
            'totalProjects' => $totalProjects,
            'totalTasksInProgress' => $totalTasksInProgress,
            'totalUsers' => $totalUsers,
            'totalTasksFinish' => $totalTasksFinish,
            'lastProjects' => $lastProjects,
            'bestResource' => $bestResource,
        ]);
    }
}
