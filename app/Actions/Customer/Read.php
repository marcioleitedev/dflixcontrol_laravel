<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class Read
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function execute($data)
    {
        $customer = Customer::where('signature_id', $data)->paginate(10);
        if (!$customer) {
            return response()->json(['message' => 'erro ao renderizr', 400]);
        }
        return response()->json($customer);
    }
}
