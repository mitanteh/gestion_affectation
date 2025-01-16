<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Projet;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Projet $projet)
    {
        return $projet->tasks()
            ->with('user')
            ->orderBy('date_limite')
            ->get();
    }

    public function store(Request $request, Projet $projet)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_limite' => 'required|date',
            'priorite' => 'required|in:Basse,Moyenne,Haute,Urgente',
            'user_id' => 'required|exists:users,id'
        ]);

        $task = $projet->tasks()->create($validated);
        return $task->load('user');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_limite' => 'required|date',
            'priorite' => 'required|in:Basse,Moyenne,Haute,Urgente',
            'user_id' => 'required|exists:users,id'
        ]);

        $task->update($validated);
        return $task->load('user');
    }

    public function updateStatus(Task $task, Request $request)
    {
        $request->validate([
            'statut' => 'required|in:A faire,En cours,En pause,Terminé'
        ]);

        $task->update([
            'statut' => $request->statut
        ]);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
} 