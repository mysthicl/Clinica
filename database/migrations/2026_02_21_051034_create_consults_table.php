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
        Schema::create('consults', function (Blueprint $table) {
            $table->id('id_consult');
            $table->foreignId('id_patient')->constrained('patients', 'id_patient');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->dateTime('date_register');
            $table->decimal('total', 8, 2);
            $table->enum('status', ['Abierta', 'Cerrada', 'Cancelada'])->default('Abierta');
            $table->foreignId('id_appointment')
              ->nullable()
              ->constrained('appointments', 'id_appointment');
            $table->string('notes', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consults');
    }
};
