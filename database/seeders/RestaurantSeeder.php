<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = [
            [
                'name' => 'La Bella Italia',
                'slug' => 'bella-italia',
                'description' => 'Auténtica cocina italiana con recetas tradicionales',
                'address_line_1' => 'Calle Mayor 123',
                'address_line_2' => '28001 Madrid',
                'phone_1' => '+34 911 222 333',
                'email' => 'info@bellaitalia.com',
                'facebook_url' => 'https://facebook.com/bellaitalia',
                'instagram_url' => 'https://instagram.com/bellaitalia',
                'meta_title' => 'La Bella Italia - Restaurante Italiano',
                'meta_description' => 'El mejor restaurante italiano de Madrid',
                'is_active' => true,
                'theme_color' => 'color_1', // Rojo - clásico italiano
            ],
            [
                'name' => 'Sushi Master',
                'slug' => 'sushi-master',
                'description' => 'Sushi y cocina japonesa de alta calidad',
                'address_line_1' => 'Avenida Diagonal 456',
                'address_line_2' => '08008 Barcelona',
                'phone_1' => '+34 933 444 555',
                'email' => 'info@sushimaster.com',
                'facebook_url' => 'https://facebook.com/sushimaster',
                'instagram_url' => 'https://instagram.com/sushimaster',
                'meta_title' => 'Sushi Master - Restaurante Japonés',
                'meta_description' => 'El mejor sushi de Barcelona',
                'is_active' => true,
                'theme_color' => 'color_4', // Azul - tema marino/japonés
            ],
            [
                'name' => 'El Asador',
                'slug' => 'el-asador',
                'description' => 'Carnes a la brasa y parrilladas',
                'address_line_1' => 'Plaza España 789',
                'address_line_2' => '41001 Sevilla',
                'phone_1' => '+34 954 666 777',
                'email' => 'info@elasador.com',
                'facebook_url' => 'https://facebook.com/elasador',
                'instagram_url' => 'https://instagram.com/elasador',
                'meta_title' => 'El Asador - Carnes a la Brasa',
                'meta_description' => 'Las mejores carnes de Sevilla',
                'is_active' => true,
                'theme_color' => 'color_2', // Naranja - cálido para asador
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::create($restaurant);
        }
    }
}
