<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tache;

class TacheController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'projet_id' => 'required|exists:projets,id',
            'module_id' => 'required|exists:modules,id',
            'lib_tache' => 'required|string|max:255',
            'etat_tache' => 'required|string|max:50',
            'statut' => 'in:actif,inactif',
        ]);

        $tache = Tache::create($validated);

        return response()->json($tache, 201);
    }

}
