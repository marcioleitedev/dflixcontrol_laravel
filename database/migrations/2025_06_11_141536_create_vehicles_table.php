<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('plate', 15)->unique();
            $table->string('brand', 50);
            $table->string('model', 50);
            $table->string('version', 50)->nullable();
            $table->integer('year');
            $table->string('color', 30)->nullable();
            $table->string('chassis', 50)->nullable()->unique();
            $table->integer('mileage')->nullable();
            $table->string('fuel_type', 20)->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->index('customer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
