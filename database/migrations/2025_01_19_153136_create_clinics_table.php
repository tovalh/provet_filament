<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            // ID autoincremental para la clínica
            $table->id();

            // Referencia al usuario administrador
            $table->foreignId('admin_id')
                ->constrained('users')
                ->onDelete('cascade');  // Si el admin es eliminado, la clínica también se elimina

            // Información básica de la clínica
            $table->string('name');           // Nombre de la clínica
            $table->string('invitation_code')
                ->unique();

            $table->string('email');          // Email de contacto
            $table->string('phone')           // Teléfono de contacto
            ->nullable();               // Puede estar vacío
            $table->text('address');          // Dirección física
            // Estado de la clínica
            $table->boolean('is_active')
                ->default(true);            // Por defecto está activa

            $table->boolean('is_trial')->default(true);
            $table->timestamp('trial_ends_at')->nullable();

            // Timestamps automáticos
            $table->timestamps();             // Crea created_at y updated_at
            $table->softDeletes();            // Permite "eliminar suavemente"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
