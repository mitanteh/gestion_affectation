<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conge;

class CongeController extends Controller
{
    public function index()
    {
        return Conge::with(['user', 'typeConge'])->get(); // Inclut les utilisateurs et types de congés
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type_conge_id' => 'required|exists:type_conges,id',
            'lib_conge' => 'required|string|max:255',
            'debut_conge' => 'required|date',
            'fin_conge' => 'required|date|after_or_equal:debut_conge',
        ]);

        $conge = Conge::create($validated);

        return response()->json($conge, 201);
    }

    public function update(Request $request, Conge $conge)
    {
        $validated = $request->validate([
            'type_conge_id' => 'exists:type_conges,id',
            'lib_conge' => 'string|max:255',
            'debut_conge' => 'date',
            'fin_conge' => 'date|after_or_equal:debut_conge',
            'statut' => 'in:en attente,approuvé,rejeté',
        ]);

        $conge->update($validated);

        return response()->json($conge);
    }

    public function destroy(Conge $conge)
    {
        $conge->delete();
        return response()->json(['message' => 'Congé supprimé avec succès']);
    }


}
