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
        Schema::create('menu_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade'); // Pertenece a un restaurante
            $table->json('name'); // Traducible: {es: "Menú Diario", ca: "Menú Diari", en: "Daily Menu"}
            $table->json('description')->nullable(); // Traducible: descripción del tipo de menú
            $table->string('photo')->nullable(); // Imagen representativa del tipo de menú
            $table->boolean('is_active')->default(true); // Activo/Inactivo
            $table->json('notes')->nullable(); // Traducible: Notas al pie del menú
            $table->json('superior_notes')->nullable(); // Traducible: Observaciones bajo el nombre de la categoría
            $table->boolean('is_principal')->default(false); // Menú por defecto que se muestra primero
            $table->boolean('is_standalone')->default(false); // Menú solitario (único)
            $table->integer('sort_order')->default(0); // Orden de visualización
            $table->timestamps();
            
            // Solo puede haber un menú principal por restaurante
            $table->index(['restaurant_id', 'is_principal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_types');
    }
};
