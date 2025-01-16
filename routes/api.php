<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\CompetenceController;
use App\Http\Controllers\TaskController;

// Authentication Routes
Route::post('/login', [UserController::class, "authenticate"]);
Route::post('/reset', [UserController::class, "resetpwd"]);
Route::post('/user/active/account/expire', [UserController::class, "activeusr"]);

Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::post('/logout', [UserController::class, "logout"]);

    // User Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/resources/{role}', [UserController::class, 'getUsersByRole']);
        
        // Backup routes
        Route::get('/{resourceId}/potential-backups', [UserController::class, 'getBackupPotentiels']); // Récupérer les backups potentiels
        Route::get('/{resourceId}/assigned-backup', [UserController::class, 'getAssignedBackup']);     // Récupérer le backup assigné
        Route::post('/{resourceId}/backup/{backupId}', [UserController::class, 'assignBackupToUser']); // Assigner un backup à un utilisateur
        Route::delete('/{resourceId}/remove-backup', [UserController::class, 'removeBackupToUser']);   // Supprimer un backup d'un utilisateur

        // Assigner une compétence à un utilisateur
        Route::post('/{userId}/competences/{skillId}', [UserController::class, 'assignCompetenceToUser']);
        Route::get('/{id}/competences', [UserController::class, 'getUserCompetences']);
        Route::delete('/{id}/competences/{competence}', [UserController::class, 'removeCompetenceToUser']);
        Route::get('/{id}/projects', [UserController::class, 'getProjetsAffectes']);
        Route::get('/{id}/tasks', [UserController::class, 'getTasksForRessource']);
        Route::get('/{user}/available-projects', [UserController::class, 'getAvailableProjects']);
        Route::post('/{user}/projects/{project}', [UserController::class, 'assignProject']);
        Route::delete('/{user}/projects/{project}', [UserController::class, 'removeProject']);
    });

    // Role Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    // Conge Routes
    Route::prefix('conges')->group(function () {
        Route::get('/', [CongeController::class, 'index']); // Accessible par tous les utilisateurs connectés
        Route::get('/{id}', [CongeController::class, 'show']);
        Route::post('/', [CongeController::class, 'store']);
        Route::put('/{id}', [CongeController::class, 'update']);
        Route::delete('/{id}', [CongeController::class, 'destroy']);
    });

    // Projet Routes
    Route::prefix('projets')->group(function () {
        Route::get('/', [ProjetController::class, 'index']);
        Route::get('/{id}', [ProjetController::class, 'show']);
        Route::post('/', [ProjetController::class, 'store']);
        Route::put('/{id}', [ProjetController::class, 'update']);
        Route::delete('/{id}', [ProjetController::class, 'destroy']);
        // Routes pour les compétences des projets
        Route::get('/{projet}/competences', [ProjetController::class, 'getProjetCompetences']);
        Route::post('/{projet}/competences/{competence}', [ProjetController::class, 'assignCompetenceToProjet']);
        Route::delete('/{projet}/competences/{competence}', [ProjetController::class, 'removeCompetenceFromProjet']);
        // Routes pour la gestion des utilisateurs du projet
        Route::get('/{projet}/eligible-users', [ProjetController::class, 'getEligibleUsers']);
        Route::get('/{projet}/users', [ProjetController::class, 'getProjetUsers']);
        Route::post('/{projet}/users/{user}', [ProjetController::class, 'assignUserToProjet']);
        Route::delete('/{projet}/users/{user}', [ProjetController::class, 'removeUserFromProjet']);
    });

    // Tache Routes
    Route::prefix('taches')->group(function () {
        Route::get('/', [TacheController::class, 'index']);
        Route::get('/{id}', [TacheController::class, 'show']);
        Route::post('/', [TacheController::class, 'store']);
        Route::put('/{id}', [TacheController::class, 'update']);
        Route::delete('/{id}', [TacheController::class, 'destroy']);
    });

    // Module Routes
    Route::prefix('modules')->group(function () {
        Route::get('/', [ModuleController::class, 'index']);
        Route::get('/{id}', [ModuleController::class, 'show']);
        Route::post('/', [ModuleController::class, 'store']);
        Route::put('/{id}', [ModuleController::class, 'update']);
        Route::delete('/{id}', [ModuleController::class, 'destroy']);
    });

    // Competence Routes
    Route::prefix('competences')->group(function () {
        Route::get('/', [CompetenceController::class, 'index']);
        Route::get('/{id}', [CompetenceController::class, 'show']);
        Route::get('/{id}', [CompetenceController::class, 'show']);
        Route::post('/', [CompetenceController::class, 'store']);
        Route::put('/{id}', [CompetenceController::class, 'update']);
        Route::delete('/{id}', [CompetenceController::class, 'destroy']);

    });

    // Routes pour les tâches
    Route::get('/projets/{projet}/tasks', [TaskController::class, 'index']);
    Route::post('/projets/{projet}/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

});
