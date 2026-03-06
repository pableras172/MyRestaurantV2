<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * Modelo DishType (Tipo de Plato / Categoría)
 * 
 * Representa las categorías de platos y bebidas que ofrece un restaurante.
 * Ejemplos: Entrantes, Hamburguesas, Carne, Pescado, Vinos, Vino Blanco, Vino Tinto, etc.
 * 
 * CARACTERÍSTICAS:
 * - Pertenece a un restaurante específico
 * - Multi-idioma: name y description son traducibles
 * - Jerarquía: Puede tener categorías padre e hijas (parent_id)
 * - Relación con tipos de menú: Una categoría puede aparecer en uno o varios menús
 * - Orden de visualización personalizable
 * 
 * JERARQUÍA:
 * Las categorías pueden organizarse en niveles:
 * - Vinos (padre)
 *   - Vino Blanco (hijo)
 *   - Vino Tinto (hijo)
 * - Entrantes (sin padre)
 * - Hamburguesas (sin padre)
 * 
 * RELACIÓN CON MENÚ:
 * Si una categoría se asocia a un tipo de menú (Menú Diario, Menú Fin de Semana),
 * solo en ese tipo de menú se mostrará la categoría y sus platos asociados.
 * 
 * USO:
 * ```php
 * // Crear categoría padre
 * $vinos = DishType::create([
 *     'restaurant_id' => 1,
 *     'name' => ['es' => 'Vinos', 'ca' => 'Vins', 'en' => 'Wines'],
 *     'is_active' => true,
 *     'sort_order' => 1
 * ]);
 * 
 * // Crear categoría hija
 * $vinoBlanco = DishType::create([
 *     'restaurant_id' => 1,
 *     'parent_id' => $vinos->id,
 *     'name' => ['es' => 'Vino Blanco', 'ca' => 'Vi Blanc', 'en' => 'White Wine'],
 *     'is_active' => true,
 *     'sort_order' => 1
 * ]);
 * 
 * // Asociar a tipos de menú
 * $vinos->menuTypes()->attach([1, 2]); // Aparece en menú diario y fin de semana
 * 
 * // Obtener categorías raíz (sin padre)
 * $raiz = DishType::whereNull('parent_id')->get();
 * 
 * // Obtener hijos de una categoría
 * $hijos = $vinos->children;
 * ```
 */
class DishType extends Model
{
    use HasTranslations;

    /**
     * Nombre de la tabla
     */
    protected $table = 'dish_types';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'restaurant_id',
        'parent_id',
        'name',
        'description',
        'photo',
        'is_active',
        'sort_order',
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
        'sort_order' => 'integer',
        'parent_id' => 'integer',
    ];

    /**
     * Relación con el restaurante
     * Un tipo de plato pertenece a un restaurante
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Relación con categoría padre
     * Una categoría puede tener una categoría padre
     */
    public function parent()
    {
        return $this->belongsTo(DishType::class, 'parent_id');
    }

    /**
     * Relación con categorías hijas
     * Una categoría puede tener múltiples categorías hijas
     */
    public function children()
    {
        return $this->hasMany(DishType::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Relación con tipos de menú (many-to-many)
     * Una categoría puede aparecer en uno o varios tipos de menú
     */
    public function menuTypes()
    {
        return $this->belongsToMany(MenuType::class, 'dish_type_menu_type');
    }

    /**
     * Relación con productos/platos (many-to-many)
     * Una categoría puede tener muchos productos
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'dish_type_product');
    }

    /**
     * Scope para obtener solo categorías activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para obtener solo categorías raíz (sin padre)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope para ordenar por sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Verifica si la categoría tiene hijos
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Verifica si la categoría es raíz (no tiene padre)
     */
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Obtiene todos los ancestros (padre, abuelo, etc.)
     */
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Obtiene todos los descendientes (hijos, nietos, etc.)
     */
    public function descendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }
}
