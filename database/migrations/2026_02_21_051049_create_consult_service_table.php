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
        Schema::create('consult_service', function (Blueprint $table) {
            $table->id('id_consult_service');
            $table->foreignId('id_consult')->constrained('consults', 'id_consult');
            $table->foreignId('id_service')->constrained('services', 'id_service');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('final_price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consult_service');
    }
};
