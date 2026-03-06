<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Modelo Allergen (Alérgeno)
 * 
 * Representa los alérgenos que pueden estar presentes en los productos.
 * Ejemplos: Gluten, Lactosa, Frutos secos, Marisco, Huevo, etc.
 * 
 * CARACTERÍSTICAS:
 * - Pertenece a un restaurante específico (cada restaurante gestiona sus alérgenos)
 * - Multi-idioma: name y description son traducibles
 * - Puede ser activado/desactivado
 * - Relación muchos-a-muchos con productos
 * 
 * TRADUCCIÓN:
 * Los campos name y description se guardan como JSON:
 * {
 *   "es": "Gluten",
 *   "ca": "Gluten", 
 *   "en": "Gluten"
 * }
 * 
 * USO:
 * ```php
 * $allergen = Allergen::create([
 *     'name' => ['es' => 'Gluten', 'ca' => 'Gluten', 'en' => 'Gluten'],
 *     'description' => [
 *         'es' => 'Presente en trigo, cebada y centeno',
 *         'ca' => 'Present en blat, ordi i sègol',
 *         'en' => 'Present in wheat, barley and rye'
 *     ],
 *     'is_active' => true
 * ]);
 * 
 * // Obtener traducción actual
 * echo $allergen->name; // Automáticamente en idioma actual
 * 
 * // Obtener traducción específica
 * echo $allergen->getTranslation('name', 'es'); // "Gluten"
 * ```
 */
class Allergen extends Model
{
    use HasTranslations;

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'photo',
        'is_active',
    ];

    /**
     * Campos traducibles (se guardan como JSON)
     */
    public array $translatable = [
        'name',
        'description',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con el restaurante
     * Un alérgeno pertenece a un restaurante
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Relación muchos-a-muchos con productos
     * Un alérgeno puede estar en muchos productos
     * Un producto puede tener muchos alérgenos
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'allergen_product');
    }

    /**
     * Scope para obtener solo alérgenos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
