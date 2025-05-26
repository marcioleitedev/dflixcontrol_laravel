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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->boolean('status')->default(true);

            $table->foreignId('plan_id')->constrained('plans');
            $table->foreignId('affiliate')->nullable()->constrained('users')->onDelete('set null');

            $table->string('cpf')->unique();
            $table->string('cep');
            $table->string('address');
            $table->string('number');
            $table->string('complement')->nullable();
            $table->string('district');
            $table->string('city');
            $table->string('state');
            $table->string('phone');
            $table->string('company_name');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
