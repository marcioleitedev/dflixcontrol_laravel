<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role; // Assuming you have a Role model

class RolerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::orderBy('id', 'desc')->paginate(10);
        return response()->json($role);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $role = new Role();
        $role->create($request->all());
        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
