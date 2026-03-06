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
        Schema::create('allergens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade'); // Pertenece a un restaurante
            $table->json('name'); // Traducible: {es: "Gluten", ca: "Gluten", en: "Gluten"}
            $table->json('description')->nullable(); // Traducible: descripción detallada
            $table->string('photo')->nullable(); // Icono/imagen del alérgeno
            $table->boolean('is_active')->default(true); // Para activar/desactivar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allergens');
    }
};
