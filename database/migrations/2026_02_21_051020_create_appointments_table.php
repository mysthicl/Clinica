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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id('id_appointment');
            $table->foreignId('id_patient')->constrained('patients', 'id_patient');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->date('scheduled_at');
            $table->enum('status', ['Agendada', 'Cancelada', 'Completada'])->default('Agendada');
            $table->string('notes', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
