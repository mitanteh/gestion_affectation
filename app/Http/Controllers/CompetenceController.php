<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competence;
use App\Models\User;

class CompetenceController extends Controller
{
    public function index()
    {
        return Competence::all();
    } 

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lib_comp' => 'required|string',
        ]);

        $competence = Competence::updateOrCreate(
            [
                'lib_comp' => $validated['lib_comp'],
                'statut' => "actif"
            ]
        );

        return response()->json($competence, 201);
    }

    public function show($id)
    {
        // Récupérer toutes les compétences avec l'utilisateur associé
        $skills = Competence::where('user_id', $id)->get();

        return response()->json($skills, 200);
    }

}
