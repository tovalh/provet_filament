<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historiales_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->date('fecha_consulta');
            $table->decimal('peso', 5, 2)->nullable();
            $table->text('motivo_consulta');
            $table->text('examen_fisico')->nullable();
            $table->text('diagnostico');
            $table->text('tratamiento');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vacunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('mascota_id')->constrained('mascotas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->foreignId('historial_medico_id')->nullable()->constrained('historiales_medicos')->onDelete('cascade');
            $table->string('nombre_vacuna');
            $table->date('fecha_aplicacion');
            $table->date('fecha_revacunacion')->nullable();
            $table->string('lote')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacunas');
        Schema::dropIfExists('historiales_medicos');
    }
};
