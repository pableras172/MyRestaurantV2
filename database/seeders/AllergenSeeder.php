<?php

namespace Database\Seeders;

use App\Models\Allergen;
use Illuminate\Database\Seeder;

class AllergenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergens = [
            [
                'name' => ['es' => 'Gluten', 'ca' => 'Gluten', 'en' => 'Gluten'],
                'description' => ['es' => 'Cereales que contienen gluten', 'ca' => 'Cereals que contenen gluten', 'en' => 'Cereals containing gluten'],
            ],
            [
                'name' => ['es' => 'Crustáceos', 'ca' => 'Crustacis', 'en' => 'Crustaceans'],
                'description' => ['es' => 'Crustáceos y productos derivados', 'ca' => 'Crustacis i productes derivats', 'en' => 'Crustaceans and products thereof'],
            ],
            [
                'name' => ['es' => 'Huevos', 'ca' => 'Ous', 'en' => 'Eggs'],
                'description' => ['es' => 'Huevos y productos derivados', 'ca' => 'Ous i productes derivats', 'en' => 'Eggs and products thereof'],
            ],
            [
                'name' => ['es' => 'Pescado', 'ca' => 'Peix', 'en' => 'Fish'],
                'description' => ['es' => 'Pescado y productos derivados', 'ca' => 'Peix i productes derivats', 'en' => 'Fish and products thereof'],
            ],
            [
                'name' => ['es' => 'Cacahuetes', 'ca' => 'Cacauets', 'en' => 'Peanuts'],
                'description' => ['es' => 'Cacahuetes y productos derivados', 'ca' => 'Cacauets i productes derivats', 'en' => 'Peanuts and products thereof'],
            ],
            [
                'name' => ['es' => 'Soja', 'ca' => 'Soja', 'en' => 'Soya'],
                'description' => ['es' => 'Soja y productos derivados', 'ca' => 'Soja i productes derivats', 'en' => 'Soya and products thereof'],
            ],
            [
                'name' => ['es' => 'Lácteos', 'ca' => 'Lactis', 'en' => 'Dairy'],
                'description' => ['es' => 'Leche y productos derivados (lactosa)', 'ca' => 'Llet i productes derivats (lactosa)', 'en' => 'Milk and products thereof (lactose)'],
            ],
            [
                'name' => ['es' => 'Frutos de cáscara', 'ca' => 'Fruits de closca', 'en' => 'Tree nuts'],
                'description' => ['es' => 'Almendras, avellanas, nueces, etc.', 'ca' => 'Ametlles, avellanes, nous, etc.', 'en' => 'Almonds, hazelnuts, walnuts, etc.'],
            ],
            [
                'name' => ['es' => 'Apio', 'ca' => 'Api', 'en' => 'Celery'],
                'description' => ['es' => 'Apio y productos derivados', 'ca' => 'Api i productes derivats', 'en' => 'Celery and products thereof'],
            ],
            [
                'name' => ['es' => 'Mostaza', 'ca' => 'Mostassa', 'en' => 'Mustard'],
                'description' => ['es' => 'Mostaza y productos derivados', 'ca' => 'Mostassa i productes derivats', 'en' => 'Mustard and products thereof'],
            ],
            [
                'name' => ['es' => 'Sésamo', 'ca' => 'Sèsam', 'en' => 'Sesame'],
                'description' => ['es' => 'Granos de sésamo', 'ca' => 'Grans de sèsam', 'en' => 'Sesame seeds'],
            ],
            [
                'name' => ['es' => 'Sulfitos', 'ca' => 'Sulfits', 'en' => 'Sulphites'],
                'description' => ['es' => 'Dióxido de azufre y sulfitos', 'ca' => 'Diòxid de sofre i sulfits', 'en' => 'Sulphur dioxide and sulphites'],
            ],
            [
                'name' => ['es' => 'Altramuces', 'ca' => 'Tramussons', 'en' => 'Lupin'],
                'description' => ['es' => 'Altramuces y productos derivados', 'ca' => 'Tramussons i productes derivats', 'en' => 'Lupin and products thereof'],
            ],
            [
                'name' => ['es' => 'Moluscos', 'ca' => 'Mol·luscs', 'en' => 'Molluscs'],
                'description' => ['es' => 'Moluscos y productos derivados', 'ca' => 'Mol·luscs i productes derivats', 'en' => 'Molluscs and products thereof'],
            ],
        ];

        foreach ($allergens as $allergen) {
            Allergen::create(array_merge($allergen, [
                'restaurant_id' => 1,
                'is_active' => true,
            ]));
        }
    }
}
