<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company');
            $table->integer('users');
            $table->float('price');
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // Inserção dos 4 planos logo após a criação da tabela
        DB::table('plans')->insert([
            [
                'name' => 'Plano Básico I',
                'company' => 1,
                'users' => 5,
                'price' => 149.90,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plano Basico II',
                'company' => 5,
                'users' => 25,
                'price' =>  259.90,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fiscal I',
                'company' => 1,
                'users' => 5,
                'price' => 259.90,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FISCAL II',
                'company' => 10,
                'users' => 25,
                'price' => 499.90,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
