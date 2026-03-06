<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DishType;
use App\Models\MenuType;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request, string $restaurant): View
    {
        $currentRestaurant = $request->get('current_restaurant');
        
        // Obtener todos los MenuTypes activos para el menú de navegación
        $menuTypes = MenuType::where('restaurant_id', $currentRestaurant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Obtener el MenuType seleccionado (por parámetro GET) o el principal
        $selectedMenuTypeId = $request->get('menu_type');
        
        if ($selectedMenuTypeId) {
            $principalMenu = MenuType::where('restaurant_id', $currentRestaurant->id)
                ->where('id', $selectedMenuTypeId)
                ->where('is_active', true)
                ->with(['dishTypes' => function ($query) {
                    $query->where('is_active', true)
                          ->whereNull('parent_id')
                          ->orderBy('sort_order');
                }])
                ->first();
        }
        
        // Si no se encontró, obtener el menú principal
        if (!isset($principalMenu) || !$principalMenu) {
            $principalMenu = MenuType::where('restaurant_id', $currentRestaurant->id)
                ->where('is_active', true)
                ->where('is_principal', true)
                ->with(['dishTypes' => function ($query) {
                    $query->where('is_active', true)
                          ->whereNull('parent_id')
                          ->orderBy('sort_order');
                }])
                ->first();
        }

        // Si aún no hay menú principal, obtener el primero activo
        if (!$principalMenu) {
            $principalMenu = MenuType::where('restaurant_id', $currentRestaurant->id)
                ->where('is_active', true)
                ->with(['dishTypes' => function ($query) {
                    $query->where('is_active', true)
                          ->whereNull('parent_id')
                          ->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->first();
        }

        // Obtener productos por categoría del menú principal
        $categoriesWithProducts = [];
        if ($principalMenu && $principalMenu->dishTypes) {
            foreach ($principalMenu->dishTypes as $category) {
                // Verificar si la categoría tiene productos directamente
                $categoryProducts = Product::where('restaurant_id', $currentRestaurant->id)
                    ->where('is_active', true)
                    ->where('is_available', true)
                    ->whereHas('dishTypes', function ($query) use ($category) {
                        $query->where('dish_types.id', $category->id);
                    })
                    ->with(['allergens', 'variants'])
                    ->orderBy('sort_order')
                    ->get();
                
                // Si la categoría padre tiene productos, agregarla
                if ($categoryProducts->count() > 0) {
                    $categoriesWithProducts[] = [
                        'category' => $category,
                        'products' => $categoryProducts,
                        'is_parent' => true
                    ];
                }
                
                // Obtener subcategorías (categorías hijas)
                $subcategories = DishType::where('restaurant_id', $currentRestaurant->id)
                    ->where('parent_id', $category->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
                
                // Para cada subcategoría, obtener sus productos
                foreach ($subcategories as $subcategory) {
                    $subcategoryProducts = Product::where('restaurant_id', $currentRestaurant->id)
                        ->where('is_active', true)
                        ->where('is_available', true)
                        ->whereHas('dishTypes', function ($query) use ($subcategory) {
                            $query->where('dish_types.id', $subcategory->id);
                        })
                        ->with(['allergens', 'variants'])
                        ->orderBy('sort_order')
                        ->get();
                    
                    if ($subcategoryProducts->count() > 0) {
                        $categoriesWithProducts[] = [
                            'category' => $subcategory,
                            'products' => $subcategoryProducts,
                            'is_parent' => false,
                            'parent_category' => $category
                        ];
                    }
                }
            }
        }

        return view('public.menu.index', compact(
            'currentRestaurant',
            'principalMenu',
            'categoriesWithProducts',
            'menuTypes'
        ));
    }

    public function category(Request $request, string $restaurant, int $categoryId): View
    {
        $currentRestaurant = $request->get('current_restaurant');
        
        $category = DishType::where('restaurant_id', $currentRestaurant->id)
            ->where('id', $categoryId)
            ->where('is_active', true)
            ->firstOrFail();

        // Obtener productos de esta categoría
        $products = Product::where('restaurant_id', $currentRestaurant->id)
            ->where('is_active', true)
            ->where('is_available', true)
            ->whereHas('dishTypes', function ($query) use ($categoryId) {
                $query->where('dish_types.id', $categoryId);
            })
            ->with(['dishTypes', 'allergens', 'variants'])
            ->orderBy('sort_order')
            ->get();

        // Obtener subcategorías
        $subcategories = DishType::where('restaurant_id', $currentRestaurant->id)
            ->where('parent_id', $categoryId)
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('public.menu.category', compact(
            'currentRestaurant',
            'category',
            'products',
            'subcategories'
        ));
    }

    public function product(Request $request, string $restaurant, int $productId): View
    {
        $currentRestaurant = $request->get('current_restaurant');
        
        $product = Product::where('restaurant_id', $currentRestaurant->id)
            ->where('id', $productId)
            ->where('is_active', true)
            ->where('is_available', true)
            ->with(['dishTypes', 'allergens', 'variants'])
            ->firstOrFail();

        // Productos relacionados (misma categoría)
        $relatedProducts = Product::where('restaurant_id', $currentRestaurant->id)
            ->where('id', '!=', $productId)
            ->where('is_active', true)
            ->where('is_available', true)
            ->whereHas('dishTypes', function ($query) use ($product) {
                $query->whereIn('dish_types.id', $product->dishTypes->pluck('id'));
            })
            ->with(['dishTypes', 'allergens', 'variants'])
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('public.menu.product', compact(
            'currentRestaurant',
            'product',
            'relatedProducts'
        ));
    }
}
