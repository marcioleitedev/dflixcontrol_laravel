<?php

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;

class Create
{

    public function execute($data)
    {

        $customer = new Customer();

        $cad = $customer->create($data);

        if (!$cad) {
            return response()->json(['message' => 'erro', 400]);
        }
        return response()->json(['message' => 'success', 200]);
    }
}
