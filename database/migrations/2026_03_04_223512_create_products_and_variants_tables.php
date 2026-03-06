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
        // Tabla principal de productos/platos
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            
            // Campos traducibles (JSON)
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('ingredients')->nullable();
            
            // Precios
            $table->decimal('price', 8, 2); // Precio base/referencia
            
            // Imágenes
            $table->string('photo1')->nullable();
            $table->string('photo2')->nullable();
            
            // Estados
            $table->boolean('is_new')->default(false); // Novedad
            $table->boolean('is_active')->default(true); // Activo en carta
            $table->boolean('is_available')->default(true); // Disponible ahora
            $table->boolean('is_unit')->default(true); // Va por unidades
            
            // Información adicional
            $table->integer('preparation_time')->nullable(); // Minutos
            $table->decimal('tax_rate', 5, 2)->nullable(); // % IVA
            $table->integer('stock_quantity')->nullable(); // Control de stock futuro
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices
            $table->index(['restaurant_id', 'is_active']);
            $table->index(['restaurant_id', 'sort_order']);
        });

        // Tabla de variantes de productos (media ración, completa, etc.)
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->json('name'); // "Media Ración", "Ración Completa", traducible
            $table->decimal('price', 8, 2); // Precio específico de esta variante
            $table->boolean('is_default')->default(false); // Variante por defecto
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index(['product_id', 'sort_order']);
        });

        // Tabla pivot: Productos - Alérgenos
        Schema::create('allergen_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allergen_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['allergen_id', 'product_id']);
        });

        // Tabla pivot: Productos - Categorías de Platos
        Schema::create('dish_type_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dish_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['dish_type_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_type_product');
        Schema::dropIfExists('allergen_product');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};
