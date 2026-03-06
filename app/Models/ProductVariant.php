<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Modelo ProductVariant (Variante de Producto)
 * 
 * Representa las diferentes variantes/tamaños de un producto.
 * Ejemplos: Media Ración, Ración Completa, Tamaño S/M/L, Para Compartir, etc.
 * 
 * CARACTERÍSTICAS:
 * - Pertenece a un producto
 * - Multi-idioma: name es traducible
 * - Cada variante tiene su propio precio
 * - Una variante puede ser marcada como predeterminada
 * 
 * USO:
 * ```php
 * // Crear variantes para un producto
 * $product->variants()->create([
 *     'name' => ['es' => 'Media Ración', 'ca' => 'Mitja Ració', 'en' => 'Half Portion'],
 *     'price' => 8.50,
 *     'is_default' => false,
 *     'sort_order' => 1
 * ]);
 * 
 * $product->variants()->create([
 *     'name' => ['es' => 'Ración Completa', 'ca' => 'Ració Completa', 'en' => 'Full Portion'],
 *     'price' => 15.00,
 *     'is_default' => true,
 *     'sort_order' => 2
 * ]);
 * ```
 */
class ProductVariant extends Model
{
    use HasTranslations;

    /**
     * Nombre de la tabla
     */
    protected $table = 'product_variants';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'is_default',
        'sort_order',
    ];

    /**
     * Campos traducibles (se guardan como JSON)
     */
    public array $translatable = [
        'name',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relación con el producto
     * Una variante pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Scope para obtener solo la variante por defecto
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
