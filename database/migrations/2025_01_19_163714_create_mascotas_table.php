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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clinic_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');

            $table->string('nombre');
            $table->string('tipo');
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['Masculino', 'Femenino', 'Desconocido']);
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
