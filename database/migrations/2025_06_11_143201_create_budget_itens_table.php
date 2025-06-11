<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('type')->default('product'); // 'product' ou 'affiliate'
            $table->string('description');
            $table->text('affiliate_link')->nullable();
            $table->string('affiliate_store')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('notes')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->index(['budget_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget_items');
    }
};
