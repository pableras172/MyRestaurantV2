<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Modelo MenuType (Tipo de Menú)
 * 
 * Representa los diferentes tipos de menús que puede ofrecer un restaurante.
 * Ejemplos: Menú Diario, Menú Fin de Semana, Menú Navidad, Menú Especial, etc.
 * 
 * CARACTERÍSTICAS:
 * - Pertenece a un restaurante específico (cada restaurante gestiona sus tipos de menú)
 * - Multi-idioma: name, description, notes y superior_notes son traducibles
 * - Puede ser activado/desactivado
 * - Tiene orden de visualización personalizable
 * - Un tipo puede ser marcado como "principal" (se muestra primero en la web)
 * - "Standalone" indica si es un menú único/independiente
 * 
 * TRADUCCIÓN:
 * Los campos traducibles se guardan como JSON:
 * {
 *   "es": "Menú Diario",
 *   "ca": "Menú Diari", 
 *   "en": "Daily Menu"
 * }
 * 
 * USO:
 * ```php
 * $menuType = MenuType::create([
 *     'restaurant_id' => 1,
 *     'name' => [
 *         'es' => 'Menú Diario',
 *         'ca' => 'Menú Diari',
 *         'en' => 'Daily Menu'
 *     ],
 *     'description' => [
 *         'es' => 'Nuestro menú del día, renovado cada jornada',
 *         'ca' => 'El nostre menú del dia, renovat cada jornada',
 *         'en' => 'Our daily menu, renewed every day'
 *     ],
 *     'is_principal' => true,
 *     'is_active' => true,
 *     'sort_order' => 1
 * ]);
 * 
 * // Obtener el menú principal del restaurante
 * $principal = MenuType::where('restaurant_id', 1)
 *     ->where('is_principal', true)
 *     ->first();
 * ```
 */
class MenuType extends Model
{
    use HasTranslations;

    /**
     * Nombre de la tabla
     */
    protected $table = 'menu_types';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'price',
        'photo',
        'is_active',
        'notes',
        'superior_notes',
        'is_principal',
        'is_standalone',
        'sort_order',
    ];

    /**
     * Campos traducibles (se guardan como JSON)
     */
    public array $translatable = [
        'name',
        'description',
        'notes',
        'superior_notes',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_principal' => 'boolean',
        'is_standalone' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relación con el restaurante
     * Un tipo de menú pertenece a un restaurante
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Relación con categorías de productos
     * Un tipo de menú puede tener muchas categorías
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relación con tipos de plato (many-to-many)
     * Un tipo de menú puede tener múltiples tipos de plato asociados
     */
    public function dishTypes()
    {
        return $this->belongsToMany(DishType::class, 'dish_type_menu_type');
    }

    /**
     * Scope para obtener solo tipos de menú activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para obtener el menú principal
     */
    public function scopePrincipal($query)
    {
        return $query->where('is_principal', true);
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
