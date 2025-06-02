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
        Schema::create('perfil_affiliates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('cpf')->unique();
            $table->string('telefone');
            $table->date('data_nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->text('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep')->nullable();
            $table->string('link_afiliado')->unique();
            $table->string('foto_perfil')->nullable();
            $table->text('descricao')->nullable();
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('tipo_conta')->nullable();
            $table->string('pix')->nullable();
            $table->decimal('comissao_percentual', 5, 2)->default(0);
            $table->string('status')->default('pendente'); // ou enum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_affiliates');
    }
};
