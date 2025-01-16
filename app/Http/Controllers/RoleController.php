<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return Role::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lib_role' => 'required|string|unique:roles|max:255',
        ]);

        $role = Role::create($validated);

        return response()->json($role, 201);
    }

}
