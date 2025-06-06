<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Categorie::all();
        return response()->json($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = new Categorie();

        if ($category->create($request->all())) {
            return response()->json(['message' => 'Categoria criado com sucesso'], 201);
        } else {
            return response()->json(['message' => 'Erro ao criar categoria'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $category = Categorie::where('id_signature' ,$id)->get();
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
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
