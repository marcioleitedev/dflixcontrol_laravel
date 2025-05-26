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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->float('value')->nullable();;
            $table->string('payment_method')->nullable();
            $table->boolean('status')->default(true);
            $table->string('transaction_id')->unique()->nullable();
            $table->date('limit_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('signature_id')->constrained('signatures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
