<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // Informações pessoais
            $table->string('name');
            $table->string('cpf')->unique();
            $table->string('rg')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable(); // 'male', 'female', 'other'

            // Dados de contato
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('emergency_phone')->nullable();

            // Endereço
            $table->string('postal_code')->nullable();
            $table->string('address');
            $table->string('address_number');
            $table->string('complement')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state', 2);

            // Informações profissionais
            $table->string('position'); // Cargo/função
            $table->string('department'); // Departamento
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('hire_date'); // Data de contratação
            $table->date('termination_date')->nullable(); // Data de desligamento
            $table->enum('status', ['active', 'on_leave', 'terminated'])->default('active');

            // Documentos
            $table->string('pis')->nullable(); // Número do PIS
            $table->string('ctps_number')->nullable(); // Número da CTPS
            $table->string('ctps_series')->nullable(); // Série da CTPS

            // Relacionamentos
            $table->foreignId('signature_id')->constrained()->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
