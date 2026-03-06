<?php

namespace Database\Seeders;

use App\Models\MenuType;
use Illuminate\Database\Seeder;

class MenuTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuTypes = [
            [
                'name' => [
                    'es' => 'Menú del Día',
                    'ca' => 'Menú del Dia',
                    'en' => 'Daily Menu',
                ],
                'description' => [
                    'es' => 'Menú completo del mediodía con primero, segundo, postre, pan y bebida',
                    'ca' => 'Menú complet del migdia amb primer, segon, postres, pa i beguda',
                    'en' => 'Complete lunch menu with starter, main, dessert, bread and drink',
                ],
                'price' => 14.50,
                'superior_notes' => [
                    'es' => 'De lunes a viernes',
                    'ca' => 'De dilluns a divendres',
                    'en' => 'Monday to Friday',
                ],
                'notes' => [
                    'es' => 'IVA incluido. No incluye bebidas alcohólicas premium',
                    'ca' => 'IVA inclòs. No inclou begudes alcohòliques premium',
                    'en' => 'VAT included. Premium alcoholic drinks not included',
                ],
                'is_principal' => true,
                'is_standalone' => false,
                'sort_order' => 1,
            ],
            [
                'name' => [
                    'es' => 'Carta',
                    'ca' => 'Carta',
                    'en' => 'À la Carte',
                ],
                'description' => [
                    'es' => 'Platos individuales de nuestra carta',
                    'ca' => 'Plats individuals de la nostra carta',
                    'en' => 'Individual dishes from our menu',
                ],
                'price' => null,
                'superior_notes' => [
                    'es' => '',
                    'ca' => '',
                    'en' => '',
                ],
                'notes' => [
                    'es' => 'Precios por plato. IVA incluido',
                    'ca' => 'Preus per plat. IVA inclòs',
                    'en' => 'Prices per dish. VAT included',
                ],
                'is_principal' => false,
                'is_standalone' => true,
                'sort_order' => 2,
            ],
            [
                'name' => [
                    'es' => 'Menú Fin de Semana',
                    'ca' => 'Menú Cap de Setmana',
                    'en' => 'Weekend Menu',
                ],
                'description' => [
                    'es' => 'Menú especial disponible sábados y domingos',
                    'ca' => 'Menú especial disponible dissabtes i diumenges',
                    'en' => 'Special menu available on Saturdays and Sundays',
                ],
                'price' => 22.00,
                'superior_notes' => [
                    'es' => 'Disponible sábados y domingos',
                    'ca' => 'Disponible dissabtes i diumenges',
                    'en' => 'Available on Saturdays and Sundays',
                ],
                'notes' => [
                    'es' => 'Incluye aperitivo, entrante, principal, postre y café',
                    'ca' => 'Inclou aperitiu, entrant, principal, postres i cafè',
                    'en' => 'Includes appetizer, starter, main, dessert and coffee',
                ],
                'is_principal' => false,
                'is_standalone' => false,
                'sort_order' => 3,
            ],
            [
                'name' => [
                    'es' => 'Menú Degustación',
                    'ca' => 'Menú Degustació',
                    'en' => 'Tasting Menu',
                ],
                'description' => [
                    'es' => 'Selección de nuestros mejores platos',
                    'ca' => 'Selecció dels nostres millors plats',
                    'en' => 'Selection of our best dishes',
                ],
                'price' => 45.00,
                'superior_notes' => [
                    'es' => 'Reserva previa recomendada',
                    'ca' => 'Reserva prèvia recomanada',
                    'en' => 'Prior booking recommended',
                ],
                'notes' => [
                    'es' => 'Maridaje de vinos disponible por 18€ adicionales',
                    'ca' => 'Maridatge de vins disponible per 18€ addicionals',
                    'en' => 'Wine pairing available for an additional 18€',
                ],
                'is_principal' => false,
                'is_standalone' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($menuTypes as $menuType) {
            MenuType::create(array_merge($menuType, [
                'restaurant_id' => 1,
                'is_active' => true,
            ]));
        }
    }
}
