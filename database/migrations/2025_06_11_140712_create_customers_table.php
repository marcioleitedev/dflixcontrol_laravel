<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // Informações básicas
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('document')->comment('CPF/CNPJ');
            $table->enum('document_type', ['cpf', 'cnpj'])->default('cpf');

            // Endereço
            $table->string('postal_code')->nullable();
            $table->string('address');
            $table->string('address_number');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state', 2);

            // Informações adicionais
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('notes')->nullable();

            // Status e relacionamentos
            $table->boolean('is_active')->default(true);
            $table->foreignId('signature_id')->constrained()->onDelete('cascade');

            // Controle
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
