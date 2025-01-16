<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;


class ModuleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lib_module' => 'required|string|max:255',
            'statut' => 'required|string|max:50',
        ]);
    
        $module = Module::create($validated);
    
        return response()->json($module, 201);
    }
}
