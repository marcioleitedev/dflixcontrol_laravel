<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\WelcomeAffiliateMail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function affiliate()
    {
        $affiliate = User::where('level', 2)->paginate(10);
        return response()->json($affiliate);
    }

    public function affiliateStore(Request $request)
    {
        $password = Str::random(10); // 10 caracteres aleatórios
        $payloadAffiliate = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($password),
            'level' => 2,
        ];
        $affiliate = User::create($payloadAffiliate);
        Mail::to($request['email'])->send(new WelcomeAffiliateMail($payloadAffiliate['name'], $request['email'], $password));
        if ($affiliate) {
            return response()->json(['message' => 'Affiliate created successfully'], 201);
        } else {
            return response()->json(['message' => 'Error creating affiliate'], 500);
        }
    }

    public function affiliateUpdate(Request $request, $id)
    {
        $affiliate = User::find($id);
        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        $affiliate->update($request->all());
        return response()->json(['message' => 'Affiliate updated successfully'], 200);
    }

    public function AffiliateDelete($id)
    {
        $affiliate = User::find($id);
        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        $affiliate->delete($id);
        return response()->json(['message' => 'Affiliate updated successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
