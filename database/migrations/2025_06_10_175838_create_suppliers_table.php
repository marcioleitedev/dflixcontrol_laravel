<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do fornecedor
            $table->string('contact_name')->nullable(); // Nome do contato principal
            $table->string('email')->nullable(); // Email de contato
            $table->string('phone')->nullable(); // Telefone principal
            $table->string('address')->nullable(); // Endereço completo
            $table->string('city')->nullable(); // Cidade
            $table->string('state')->nullable(); // Estado
            $table->string('postal_code')->nullable(); // CEP
            $table->string('country')->nullable(); // País
            $table->string('cnpj_cpf')->nullable(); // CNPJ ou CPF
            $table->string('ie')->nullable(); // Inscrição Estadual (se aplicável)
            $table->text('notes')->nullable(); // Observações/notas adicionais
            $table->string('payment_terms')->nullable(); // Condições de pagamento
            $table->foreignId('id_signature')->constrained('signatures'); // Relacionamento com assinatura (se aplicável)
            $table->boolean('active')->default(true); // Status do fornecedor
            $table->timestamps(); // created_at e updated_at
            $table->softDeletes(); // deleted_at (para exclusão lógica)
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};