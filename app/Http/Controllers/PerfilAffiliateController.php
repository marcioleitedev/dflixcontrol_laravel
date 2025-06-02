<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilAffiliate; // Assuming you have a model for PerfilAffiliate

class PerfilAffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perfilAffiliates = PerfilAffiliate::all(); // Fetch all PerfilAffiliate records
        return response()->json($perfilAffiliates, 200); // Return the records as JSON
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $perfilAffiliates = new PerfilAffiliate();
        $perfilAffiliates->create($request->all());
        if (! $perfilAffiliates) {
            return response()->json(['message' => 'Error creating Perfil Affiliate'], 500);
        }
        return response()->json(['message' => 'Perfil Affiliate created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perfilAffiliates = PerfilAffiliate::where('user_id', $id)->first();
        if (!$perfilAffiliates) {
            return response()->json(['message' => 'Perfil Affiliate not found'], 404);
        }
        return response()->json($perfilAffiliates, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $perfilAffiliates = PerfilAffiliate::where('user_id', $request['user_id'])->first();
        if (!$perfilAffiliates) {
            return response()->json(['message' => 'Perfil Affiliate not found'], 404);
        }
        $perfilAffiliates->update($request->all());
        return response()->json(['message' => 'Perfil Affiliate updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $perfilAffiliates = PerfilAffiliate::where('user_id', $id)->first();
        if (!$perfilAffiliates) {
            return response()->json(['message' => 'Perfil Affiliate not found'], 404);
        }
        $perfilAffiliates->delete(); // Delete the PerfilAffiliate record
        return response()->json(['message' => 'Perfil Affiliate deleted successfully'], 200);
    }
}
