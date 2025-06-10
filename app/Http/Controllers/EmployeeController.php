<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
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
        $employee = Employee::create($request->all());
        if (!$employee) {
            return response()->json(['message' => 'erro', 400]);
        }
        return response()->json(['message' => 'sucesso', 200]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $query = Employee::where('signature_id', $id);

            // Filtro de busca
            if (request()->has('search') && !empty(request('search'))) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('cnpj', 'like', "%{$search}%");
                });
            }

            // Paginação
            $perPage = request()->has('per_page') ? request('per_page') : 10;
            $empregados = $query->paginate($perPage);

            if ($empregados->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'message' => 'Nenhum fornecedor encontrado',
                    'status' => 404
                ], 404);
            }

            return response()->json([
                'data' => $empregados->items(),
                'current_page' => $empregados->currentPage(),
                'last_page' => $empregados->lastPage(),
                'per_page' => $empregados->perPage(),
                'total' => $empregados->total(),
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
        $empregado = Employee::find($id);

        if (!$empregado) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $updated = $empregado->update($request->all());

        if ($updated) {
            return response()->json(['message' => 'Atualizado com sucesso'], 200);
        }

        return response()->json(['message' => 'Erro ao atualizar'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empregado = Employee::where('id', $id)->first();

        if (!$empregado) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        if ($empregado->delete()) {
            return response()->json(['message' => 'Deletado com sucesso'], 200);
        }

        return response()->json(['message' => 'Erro ao deletar'], 400);
    }
}
