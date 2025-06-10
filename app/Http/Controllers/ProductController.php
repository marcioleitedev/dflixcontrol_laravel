<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Product();

        $create = $product->create($request->all());
        if(!$create){
            return response()->json(['message' => 'erro ao criar produto']);
        }
        return response()->json(['message' => 'produto criado com sucesso']);
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = Product::with(['category'])
            ->where('id_signature', $id);
        
        // Filtro de busca
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Paginação
        if (request()->has('per_page')) {
            $perPage = request('per_page');
            $products = $query->paginate($perPage);
        } else {
            $products = $query->get();
        }
        
        if ($products->isEmpty()) {
            return response()->json(['error' => 'Nenhum produto encontrado'], 404);
        }
        
        return response()->json($products);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);
    
        if(!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        
        $updated = $product->update($request->all());
        
        if($updated) {
            return response()->json(['message' => 'Atualizado com sucesso'], 200);
        }
        
        return response()->json(['message' => 'Erro ao atualizar'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('id' ,$id)->first();
    
        if(!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        
        if($product->delete()) {
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        }
        
        return response()->json(['message' => 'Erro ao deletar'], 400);
    }
}
