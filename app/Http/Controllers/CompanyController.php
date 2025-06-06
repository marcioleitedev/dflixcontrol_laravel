<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Signature;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function index(){
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //vertifica se usuario pode cadastrar mais empresas
        $signature = Signature::where('id', $request->signature_id)->with(['plan'])->first();
        // return response()->json(['message' => $signature->plan->company], 201);

        $limit = $signature->plan->company;
        $count = Company::where('signature_id', $request->signature_id)->count();

        if($count >= $limit) {
            return response()->json(['message' => 'Limite de empresas atingido'], 400);
        }

        $company = new Company();
        $company->create($request->all());
        return response()->json(['message' => 'Empresa criada com sucesso'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empresa = Company::where('signature_id', $id)->get();
        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }
        return response()->json($empresa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::find($id);
        $company->update($request->all());
        if (!$company) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }
        return response()->json(['message' => 'Empresa atualizada com sucesso'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::find($id);
        $company->delete();
        if (!$company) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }
        return response()->json(['message' => 'Empresa excluída com sucesso'], 200);
    }
}
