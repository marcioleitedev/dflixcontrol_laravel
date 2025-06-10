<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
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
        $supplier = Supplier::create($request->all());

        if(!$supplier){
            return response()->json(['message' => 'erro ao cadastrar', 400]);
        }
        return response()->json(['message' => 'sucesso', 200]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $query = Supplier::where('id_signature', $id);
            
            // Filtro de busca
            if (request()->has('search') && !empty(request('search'))) {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('cnpj', 'like', "%{$search}%");
                });
            }
            
            // Paginação
            $perPage = request()->has('per_page') ? request('per_page') : 10;
            $fornecedores = $query->paginate($perPage);
            
            if ($fornecedores->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'message' => 'Nenhum fornecedor encontrado',
                    'status' => 404
                ], 404);
            }
            
            return response()->json([
                'data' => $fornecedores->items(),
                'current_page' => $fornecedores->currentPage(),
                'last_page' => $fornecedores->lastPage(),
                'per_page' => $fornecedores->perPage(),
                'total' => $fornecedores->total(),
                'status' => 200
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar fornecedores',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supllier = Supplier::where('id' , $id)->first();

        $supllier->update($request->all());

    
        if(!$supllier) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }
        
        $updated = $supllier->update($request->all());
        
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
        $supplier = Supplier::find($id);

        $destroy = $supplier->delete();

        if(! $destroy){
            return response()->json(['message' => 'erro a odeletar' , 400]);
        }
        return response()->json(['message' => 'deletado com sucesso' , 200]);
    }
}
