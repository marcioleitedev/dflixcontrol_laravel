<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Actions\Customer\Create;
use App\Actions\Customer\Delete;
use App\Actions\Customer\Read;
use App\Actions\Customer\Update;

class CustomerController extends Controller
{
    protected $create;
    protected $update;
    protected $read;
    protected $delete;

    public function __construct(Create $create, Delete $delete, Read $read, Update $update)
    {
        $this->create = $create;
        $this->update = $update;
        $this->read = $read;
        $this->delete = $delete;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Customer::paginate(10);
        if (!$customer) {
            return response()->json(['message' => 'erro ao renderizar', 400]);
        }
        return response()->json($customer, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->create->execute($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->read->execute($id);
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
