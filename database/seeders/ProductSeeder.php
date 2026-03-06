<?php

namespace Database\Seeders;

use App\Models\Allergen;
use App\Models\DishType;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener categorías
        $ensaladas = DishType::where('restaurant_id', 1)->where('name->es', 'Ensaladas')->first();
        $sopas = DishType::where('restaurant_id', 1)->where('name->es', 'Sopas y Cremas')->first();
        $carne = DishType::where('restaurant_id', 1)->where('name->es', 'Carnes')->first();
        $pescado = DishType::where('restaurant_id', 1)->where('name->es', 'Pescados')->first();
        $pasta = DishType::where('restaurant_id', 1)->where('name->es', 'Pasta')->first();
        $postres = DishType::where('restaurant_id', 1)->where('name->es', 'Postres')->first();
        $tartas = DishType::where('restaurant_id', 1)->where('name->es', 'Tartas')->first();

        // Obtener alergenos
        $gluten = Allergen::where('restaurant_id', 1)->where('name->es', 'Gluten')->first();
        $lacteos = Allergen::where('restaurant_id', 1)->where('name->es', 'Lácteos')->first();
        $huevos = Allergen::where('restaurant_id', 1)->where('name->es', 'Huevos')->first();
        $pescadoAlergeno = Allergen::where('restaurant_id', 1)->where('name->es', 'Pescado')->first();
        $frutosCascara = Allergen::where('restaurant_id', 1)->where('name->es', 'Frutos de cáscara')->first();
        $moluscos = Allergen::where('restaurant_id', 1)->where('name->es', 'Moluscos')->first();

        // ENSALADAS
        $ensaladaCesar = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Ensalada César',
                'ca' => 'Amanida Cèsar',
                'en' => 'Caesar Salad',
            ],
            'description' => [
                'es' => 'Lechuga romana, pollo a la parrilla, parmesano, picatostes y salsa césar',
                'ca' => 'Enciam romaní, pollastre a la graella, parmesà, trossets de pa i salsa cèsar',
                'en' => 'Romaine lettuce, grilled chicken, parmesan, croutons and caesar dressing',
            ],
            'ingredients' => [
                'es' => 'Lechuga, pollo, queso parmesano, pan, aceite, ajo, anchoas, mostaza, limón',
                'ca' => 'Enciam, pollastre, formatge parmesà, pa, oli, all, anxoves, mostassa, llimona',
                'en' => 'Lettuce, chicken, parmesan cheese, bread, oil, garlic, anchovies, mustard, lemon',
            ],
            'price' => 9.50,
            'preparation_time' => 10,
            'tax_rate' => 10.00,
            'is_new' => false,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => false,
            'stock_quantity' => null,
            'sort_order' => 1,
        ]);
        $ensaladaCesar->dishTypes()->attach([$ensaladas->id]);
        $ensaladaCesar->allergens()->attach([$gluten->id, $lacteos->id, $pescadoAlergeno->id]);

        // SOPAS
        $cremaVerduras = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Crema de Verduras',
                'ca' => 'Crema de Verdures',
                'en' => 'Vegetable Cream',
            ],
            'description' => [
                'es' => 'Crema suave de verduras frescas de temporada',
                'ca' => 'Crema suau de verdures fresques de temporada',
                'en' => 'Smooth cream of fresh seasonal vegetables',
            ],
            'ingredients' => [
                'es' => 'Calabacín, zanahoria, puerro, patata, cebolla, nata, sal, pimienta',
                'ca' => 'Carbassó, pastanaga, porro, patata, ceba, nata, sal, pebre',
                'en' => 'Zucchini, carrot, leek, potato, onion, cream, salt, pepper',
            ],
            'price' => 6.50,
            'preparation_time' => 5,
            'tax_rate' => 10.00,
            'is_new' => false,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => false,
            'stock_quantity' => null,
            'sort_order' => 1,
        ]);
        $cremaVerduras->dishTypes()->attach([$sopas->id]);
        $cremaVerduras->allergens()->attach([$lacteos->id]);

        // CARNES
        $solomillo = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Solomillo de Ternera',
                'ca' => 'Filet de Vedella',
                'en' => 'Beef Tenderloin',
            ],
            'description' => [
                'es' => 'Solomillo de ternera a la parrilla con guarnición de patatas',
                'ca' => 'Filet de vedella a la graella amb guarnició de patates',
                'en' => 'Grilled beef tenderloin with potato garnish',
            ],
            'ingredients' => [
                'es' => 'Solomillo de ternera (250g), patatas, aceite, sal, pimienta',
                'ca' => 'Filet de vedella (250g), patates, oli, sal, pebre',
                'en' => 'Beef tenderloin (250g), potatoes, oil, salt, pepper',
            ],
            'price' => 18.50,
            'preparation_time' => 20,
            'tax_rate' => 10.00,
            'is_new' => false,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => false,
            'stock_quantity' => null,
            'sort_order' => 1,
        ]);
        $solomillo->dishTypes()->attach([$carne->id]);

        // PESCADOS
        $lubina = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Lubina a la Sal',
                'ca' => 'Llobarro a la Sal',
                'en' => 'Sea Bass in Salt',
            ],
            'description' => [
                'es' => 'Lubina fresca al horno en costra de sal',
                'ca' => 'Llobarro fresc al forn en crosta de sal',
                'en' => 'Fresh sea bass baked in salt crust',
            ],
            'ingredients' => [
                'es' => 'Lubina (400g), sal gorda, limón',
                'ca' => 'Llobarro (400g), sal grossa, llimona',
                'en' => 'Sea bass (400g), coarse salt, lemon',
            ],
            'price' => 16.00,
            'preparation_time' => 25,
            'tax_rate' => 10.00,
            'is_new' => true,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => false,
            'stock_quantity' => null,
            'sort_order' => 1,
        ]);
        $lubina->dishTypes()->attach([$pescado->id]);
        $lubina->allergens()->attach([$pescadoAlergeno->id]);

        // PASTA con variantes
        $carbonara = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Pasta Carbonara',
                'ca' => 'Pasta Carbonara',
                'en' => 'Carbonara Pasta',
            ],
            'description' => [
                'es' => 'Pasta fresca con salsa carbonara tradicional',
                'ca' => 'Pasta fresca amb salsa carbonara tradicional',
                'en' => 'Fresh pasta with traditional carbonara sauce',
            ],
            'ingredients' => [
                'es' => 'Pasta, huevo, panceta, queso pecorino, pimienta negra',
                'ca' => 'Pasta, ou, panceta, formatge pecorino, pebre negre',
                'en' => 'Pasta, egg, bacon, pecorino cheese, black pepper',
            ],
            'price' => 11.00,
            'preparation_time' => 15,
            'tax_rate' => 10.00,
            'is_new' => false,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => false,
            'stock_quantity' => null,
            'sort_order' => 1,
        ]);
        $carbonara->dishTypes()->attach([$pasta->id]);
        $carbonara->allergens()->attach([$gluten->id, $huevos->id, $lacteos->id]);

        // Variantes de Carbonara
        ProductVariant::create([
            'product_id' => $carbonara->id,
            'name' => [
                'es' => 'Media Ración',
                'ca' => 'Mitja Ració',
                'en' => 'Half Portion',
            ],
            'price' => 6.50,
            'is_default' => false,
            'sort_order' => 1,
        ]);

        ProductVariant::create([
            'product_id' => $carbonara->id,
            'name' => [
                'es' => 'Ración Completa',
                'ca' => 'Ració Completa',
                'en' => 'Full Portion',
            ],
            'price' => 11.00,
            'is_default' => true,
            'sort_order' => 2,
        ]);

        // POSTRES
        $tiramisu = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Tiramisú Casero',
                'ca' => 'Tiramisú Casolà',
                'en' => 'Homemade Tiramisu',
            ],
            'description' => [
                'es' => 'Postre italiano tradicional con café y mascarpone',
                'ca' => 'Postres italià tradicional amb cafè i mascarpone',
                'en' => 'Traditional Italian dessert with coffee and mascarpone',
            ],
            'ingredients' => [
                'es' => 'Bizcocho de soletilla, café, mascarpone, huevo, azúcar, cacao',
                'ca' => 'Bescuit de soleta, cafè, mascarpone, ou, sucre, cacau',
                'en' => 'Ladyfinger biscuit, coffee, mascarpone, egg, sugar, cocoa',
            ],
            'price' => 5.50,
            'preparation_time' => 5,
            'tax_rate' => 10.00,
            'is_new' => false,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => true,
            'stock_quantity' => 8,
            'sort_order' => 1,
        ]);
        $tiramisu->dishTypes()->attach([$postres->id, $tartas->id]);
        $tiramisu->allergens()->attach([$gluten->id, $huevos->id, $lacteos->id]);

        $tartaQueso = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Tarta de Queso',
                'ca' => 'Pastís de Formatge',
                'en' => 'Cheesecake',
            ],
            'description' => [
                'es' => 'Tarta de queso cremosa al estilo americano',
                'ca' => 'Pastís de formatge cremós a l\'estil americà',
                'en' => 'Creamy American-style cheesecake',
            ],
            'ingredients' => [
                'es' => 'Queso crema, galletas, mantequilla, azúcar, huevo, nata',
                'ca' => 'Formatge crema, galetes, mantega, sucre, ou, nata',
                'en' => 'Cream cheese, cookies, butter, sugar, egg, cream',
            ],
            'price' => 5.00,
            'preparation_time' => 5,
            'tax_rate' => 10.00,
            'is_new' => true,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => true,
            'stock_quantity' => 6,
            'sort_order' => 2,
        ]);
        $tartaQueso->dishTypes()->attach([$postres->id, $tartas->id]);
        $tartaQueso->allergens()->attach([$gluten->id, $huevos->id, $lacteos->id]);

        // Paella (con stock limitado)
        $entrantes = DishType::where('restaurant_id', 1)->where('name->es', 'Entrantes')->first();
        $paella = Product::create([
            'restaurant_id' => 1,
            'name' => [
                'es' => 'Paella Valenciana',
                'ca' => 'Paella Valenciana',
                'en' => 'Valencian Paella',
            ],
            'description' => [
                'es' => 'Paella tradicional valenciana con pollo y conejo (mínimo 2 personas)',
                'ca' => 'Paella tradicional valenciana amb pollastre i conill (mínim 2 persones)',
                'en' => 'Traditional Valencian paella with chicken and rabbit (minimum 2 people)',
            ],
            'ingredients' => [
                'es' => 'Arroz, pollo, conejo, judía verde, garrofón, tomate, azafrán, aceite, sal',
                'ca' => 'Arròs, pollastre, conill, mongeta tendra, garrofó, tomàquet, safrà, oli, sal',
                'en' => 'Rice, chicken, rabbit, green beans, lima beans, tomato, saffron, oil, salt',
            ],
            'price' => 14.00,
            'preparation_time' => 45,
            'tax_rate' => 10.00,
            'is_new' => false,
            'is_active' => true,
            'is_available' => true,
            'is_unit' => false,
            'stock_quantity' => null,
            'sort_order' => 1,
        ]);
        $paella->dishTypes()->attach([$entrantes->id]);

        ProductVariant::create([
            'product_id' => $paella->id,
            'name' => [
                'es' => 'Para 2 personas',
                'ca' => 'Per a 2 persones',
                'en' => 'For 2 people',
            ],
            'price' => 14.00,
            'is_default' => true,
            'sort_order' => 1,
        ]);

        ProductVariant::create([
            'product_id' => $paella->id,
            'name' => [
                'es' => 'Para 4 personas',
                'ca' => 'Per a 4 persones',
                'en' => 'For 4 people',
            ],
            'price' => 26.00,
            'is_default' => false,
            'sort_order' => 2,
        ]);
    }
}
