<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;
use App\Models\User;
use App\Models\Role;
use App\Mail\WelcomeMail;
use App\Mail\ForgotMail;
use App\Models\Payment;
use App\Models\UserRoler;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class SignatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signature = Signature::with(['plan', 'payments', 'affiliate', 'user'])->paginate(10);

        return response()->json($signature);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $signature = Signature::create($request->all());

        if ($signature) {
            $password = Str::random(10); // 10 caracteres aleatórios
            // create a user
            $payloadUser = [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($password),
                'level' => 1,
                'id_signature' => $signature->id,
            ];
            $user = User::create($payloadUser);

            // create Rolers
            $roles = Role::where('level', 1)->get();
            $payloadRoles = [];
            foreach ($roles as $role) {
                $payloadRoles[] = [
                    'user_id' => $user->id,
                    'roles_id' => $role->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            $cadRoler = UserRoler::insert($payloadRoles);
            if (!$cadRoler) {
                return response()->json(['message' => 'Erro ao atribuir roles ao usuário'], 500);
            }

            // creete payment
            $payloadPayment = [
                'signature_id' => $signature->id,
                'value' => 0,
                'payment_method' => 'teste',
                'status' => 1,
                'transation_id' => 1,
                'limit_date' => now()->addDays(30),
            ];
            Payment::create($payloadPayment);

            // create company primary

            $payloadCompany = [
                'name' => $request['company_name'],
                'cep' => $request['cep'],
                'address' => $request['address'],
                'number' => $request['number'],
                'complement' => $request['complement'],
                'district' => $request['district'],
                'city' => $request['city'],
                'state' => $request['state'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                // 'cnpj' => $request['cnpj'] ?? '',
                'signature_id' => $signature->id,
            ];

            Company::insert($payloadCompany);

            Mail::to($request['email'])->send(new WelcomeMail($payloadUser['name'], $request['email'], $password));
            return response()->json(['message' => 'Assinatura criada com sucesso'], 201);
        } else {
            return response()->json(['message' => 'Erro ao criar assinatura'], 500);
        }
    }

    public function forgot(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        if ($user) {
            $password = Str::random(10);
            $user->password = Hash::make($password);
            $user->save();
            Mail::to($request['email'])->send(new ForgotMail($request['email']));
            return response()->json(['message' => 'Senha alterada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }

    public function change(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
        if ($user) {
            $user->password = Hash::make($request['password']);
            $user->save();
            return response()->json(['message' => 'Senha alterada com sucesso'], 200);
        } else {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Signature::with(['payments', 'plan'])->where('id', $id)->first();
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function showUser(string $id)
    {
        $user = User::where('id_signature', $id)->with('subscription')->paginate(10);
        return response()->json($user);
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
