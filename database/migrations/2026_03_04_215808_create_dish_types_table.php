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
        Schema::create('dish_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade'); // Pertenece a un restaurante
            $table->foreignId('parent_id')->nullable()->constrained('dish_types')->onDelete('cascade'); // Categoría padre (jerárquica)
            $table->json('name'); // Traducible: {es: "Entrantes", ca: "Entrants", en: "Starters"}
            $table->json('description')->nullable(); // Traducible: descripción de la categoría
            $table->boolean('is_active')->default(true); // Activo/Inactivo
            $table->integer('sort_order')->default(0); // Orden de visualización
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['restaurant_id', 'parent_id']);
            $table->index(['restaurant_id', 'sort_order']);
        });
        
        // Tabla pivot para la relación many-to-many con menu_types
        Schema::create('dish_type_menu_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dish_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_type_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Un dish_type puede aparecer solo una vez en cada menu_type
            $table->unique(['dish_type_id', 'menu_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_type_menu_type');
        Schema::dropIfExists('dish_types');
    }
};
