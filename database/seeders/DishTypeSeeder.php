<?php

namespace Database\Seeders;

use App\Models\DishType;
use App\Models\MenuType;
use Illuminate\Database\Seeder;

class DishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener MenuTypes
        $menuDelDia = MenuType::where('restaurant_id', 1)->where('name->es', 'Menú del Día')->first();
        $carta = MenuType::where('restaurant_id', 1)->where('name->es', 'Carta')->first();

        // Categorías principales
        $entrantes = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => null,
            'name' => [
                'es' => 'Entrantes',
                'ca' => 'Entrants',
                'en' => 'Starters',
            ],
            'description' => [
                'es' => 'Platos para comenzar la comida',
                'ca' => 'Plats per començar el menjar',
                'en' => 'Dishes to start your meal',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $principales = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => null,
            'name' => [
                'es' => 'Platos Principales',
                'ca' => 'Plats Principals',
                'en' => 'Main Courses',
            ],
            'description' => [
                'es' => 'Platos principales de la carta',
                'ca' => 'Plats principals de la carta',
                'en' => 'Main dishes from our menu',
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $postres = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => null,
            'name' => [
                'es' => 'Postres',
                'ca' => 'Postres',
                'en' => 'Desserts',
            ],
            'description' => [
                'es' => 'Dulces y postres caseros',
                'ca' => 'Dolços i postres casolans',
                'en' => 'Homemade sweets and desserts',
            ],
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Subcategorías de Entrantes
        $ensaladas = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => $entrantes->id,
            'name' => [
                'es' => 'Ensaladas',
                'ca' => 'Amanides',
                'en' => 'Salads',
            ],
            'description' => [
                'es' => 'Ensaladas frescas y variadas',
                'ca' => 'Amanides fresques i variades',
                'en' => 'Fresh and varied salads',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $sopas = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => $entrantes->id,
            'name' => [
                'es' => 'Sopas y Cremas',
                'ca' => 'Sopes i Cremes',
                'en' => 'Soups and Creams',
            ],
            'description' => [
                'es' => 'Sopas y cremas caseras',
                'ca' => 'Sopes i cremes casolanes',
                'en' => 'Homemade soups and creams',
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Subcategorías de Principales
        $carne = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => $principales->id,
            'name' => [
                'es' => 'Carnes',
                'ca' => 'Carns',
                'en' => 'Meat',
            ],
            'description' => [
                'es' => 'Platos de carne',
                'ca' => 'Plats de carn',
                'en' => 'Meat dishes',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $pescado = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => $principales->id,
            'name' => [
                'es' => 'Pescados',
                'ca' => 'Peixos',
                'en' => 'Fish',
            ],
            'description' => [
                'es' => 'Pescados frescos del día',
                'ca' => 'Peixos frescos del dia',
                'en' => 'Fresh fish of the day',
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $pasta = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => $principales->id,
            'name' => [
                'es' => 'Pasta',
                'ca' => 'Pasta',
                'en' => 'Pasta',
            ],
            'description' => [
                'es' => 'Pastas italianas',
                'ca' => 'Pastes italianes',
                'en' => 'Italian pasta',
            ],
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Subcategorías de Postres
        $helados = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => $postres->id,
            'name' => [
                'es' => 'Helados',
                'ca' => 'Gelats',
                'en' => 'Ice Creams',
            ],
            'description' => [
                'es' => 'Helados artesanales',
                'ca' => 'Gelats artesanals',
                'en' => 'Artisan ice creams',
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $tartas = DishType::create([
            'restaurant_id' => 1,
            'parent_id' => $postres->id,
            'name' => [
                'es' => 'Tartas',
                'ca' => 'Pastissos',
                'en' => 'Cakes',
            ],
            'description' => [
                'es' => 'Tartas caseras',
                'ca' => 'Pastissos casolans',
                'en' => 'Homemade cakes',
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Asociar categorías a MenuTypes
        if ($menuDelDia) {
            $menuDelDia->dishTypes()->attach([
                $entrantes->id,
                $principales->id,
                $postres->id,
            ]);
        }

        if ($carta) {
            $carta->dishTypes()->attach([
                $entrantes->id,
                $ensaladas->id,
                $sopas->id,
                $principales->id,
                $carne->id,
                $pescado->id,
                $pasta->id,
                $postres->id,
                $helados->id,
                $tartas->id,
            ]);
        }
    }
}
