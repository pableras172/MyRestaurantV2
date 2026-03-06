<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * Modelo Product (Producto/Plato)
 * 
 * Representa los platos y productos que ofrece un restaurante en su carta.
 * 
 * CARACTERÍSTICAS:
 * - Pertenece a un restaurante específico
 * - Multi-idioma: name, description, ingredients son traducibles
 * - Puede tener variantes (media ración, ración completa)
 * - Relación con alérgenos (many-to-many)
 * - Relación con categorías/tipos de plato (many-to-many)
 * - Control de disponibilidad y stock
 * - Soft deletes habilitado
 * 
 * ESTADOS:
 * - is_new: Marcado como novedad en la carta
 * - is_active: Aparece en la carta
 * - is_available: Disponible para pedidos (puede estar activo pero no disponible por falta de stock)
 * - is_unit: Se vende por unidades (precio unitario)
 * 
 * VARIANTES:
 * Un producto puede tener múltiples variantes con precios diferentes:
 * - Media Ración: 8.50€
 * - Ración Completa: 15.00€
 * 
 * USO:
 * ```php
 * $product = Product::create([
 *     'restaurant_id' => 1,
 *     'name' => ['es' => 'Paella Valenciana', 'ca' => 'Paella Valenciana', 'en' => 'Valencian Paella'],
 *     'description' => ['es' => 'Paella tradicional con pollo y conejo', ...],
 *     'ingredients' => ['es' => 'Arroz, pollo, conejo, judía verde, garrofón', ...],
 *     'price' => 15.00,
 *     'is_new' => true,
 *     'preparation_time' => 30
 * ]);
 * 
 * // Añadir alérgenos
 * $product->allergens()->attach([1, 3]); // gluten, marisco
 * 
 * // Añadir categorías
 * $product->dishTypes()->attach([2, 5]); // Arroces, Especialidades
 * 
 * // Crear variantes
 * $product->variants()->create([
 *     'name' => ['es' => 'Media Ración', ...],
 *     'price' => 8.50,
 *     'is_default' => false
 * ]);
 * ```
 */
class Product extends Model
{
    use HasTranslations, SoftDeletes;

    /**
     * Nombre de la tabla
     */
    protected $table = 'products';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'ingredients',
        'price',
        'photo1',
        'photo2',
        'is_new',
        'is_active',
        'is_available',
        'is_unit',
        'preparation_time',
        'tax_rate',
        'stock_quantity',
        'sort_order',
    ];

    /**
     * Campos traducibles (se guardan como JSON)
     */
    public array $translatable = [
        'name',
        'description',
        'ingredients',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'is_new' => 'boolean',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'is_unit' => 'boolean',
        'price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'preparation_time' => 'integer',
        'stock_quantity' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Relación con el restaurante
     * Un producto pertenece a un restaurante
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Relación con variantes del producto
     * Un producto puede tener múltiples variantes (media ración, completa, etc.)
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    /**
     * Relación con alérgenos (many-to-many)
     * Un producto puede contener múltiples alérgenos
     */
    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'allergen_product');
    }

    /**
     * Relación con tipos de plato/categorías (many-to-many)
     * Un producto puede pertenecer a múltiples categorías
     */
    public function dishTypes()
    {
        return $this->belongsToMany(DishType::class, 'dish_type_product');
    }

    /**
     * Scope para obtener solo productos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para obtener solo productos disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope para obtener productos nuevos/novedades
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Verifica si el producto tiene variantes
     */
    public function hasVariants(): bool
    {
        return $this->variants()->count() > 0;
    }

    /**
     * Obtiene la variante por defecto
     */
    public function defaultVariant()
    {
        return $this->variants()->where('is_default', true)->first();
    }

    /**
     * Verifica si el producto tiene stock disponible
     */
    public function hasStock(): bool
    {
        if ($this->stock_quantity === null) {
            return true; // Si no se controla stock, siempre hay
        }
        
        return $this->stock_quantity > 0;
    }
}
