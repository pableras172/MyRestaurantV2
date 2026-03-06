<?php

return [
    'title' => 'Productes',
    'singular' => 'Producte',
    
    // Tabs
    'tab_basic' => 'Informació Bàsica',
    'tab_images' => 'Imatges',
    'tab_categories' => 'Categories i Al·lèrgens',
    'tab_variants' => 'Variants',
    'tab_config' => 'Configuració',
    
    // Camps bàsics
    'name' => 'Nom',
    'description' => 'Descripció',
    'ingredients' => 'Ingredients',
    'ingredients_helper' => 'Llista d\'ingredients del plat',
    'price' => 'Preu',
    'price_helper' => 'Preu base del producte (sense variants)',
    'preparation_time' => 'Temps de Preparació',
    'preparation_time_helper' => 'Temps aproximat de preparació en minuts',
    'minutes' => 'minuts',
    'tax_rate' => 'Taxa d\'Impost (IVA)',
    'tax_rate_helper' => 'Percentatge d\'IVA aplicable (ex: 10 per 10%)',
    'sort_order' => 'Ordre',
    'sort_order_helper' => 'Número per ordenar el producte a la carta',
    
    // Imatges
    'photo' => 'Foto',
    'photo1' => 'Foto Principal',
    'photo2' => 'Foto Secundària',
    'photo_helper' => 'Mida màxima: 1MB. Formats: JPG, PNG',
    
    // Categories i al·lèrgens
    'dish_types' => 'Categories',
    'dish_types_helper' => 'Selecciona les categories on apareixerà aquest producte',
    'allergens' => 'Al·lèrgens',
    'allergens_helper' => 'Selecciona els al·lèrgens que conté aquest producte',
    'allergens_count' => 'Al·lèrgens',
    
    // Variants
    'variants' => 'Variants',
    'variants_description' => 'Les variants permeten oferir el mateix producte en diferents mides o presentacions (ex: Mitja Ració, Ració Completa)',
    'variant_name' => 'Nom de la Variant',
    'variant_price' => 'Preu',
    'variant_default' => 'Per Defecte',
    'variant_default_helper' => 'Marcar com a opció predeterminada',
    
    // Configuració
    'is_active' => 'Actiu',
    'is_active_helper' => 'El producte apareix a la carta',
    'is_available' => 'Disponible',
    'is_available_helper' => 'El producte està disponible per comandes (pot estar actiu però no disponible per falta d\'estoc)',
    'is_new' => 'Novetat',
    'is_new_helper' => 'Marcar com a novetat per destacar-lo a la carta',
    'is_unit' => 'Per Unitats',
    'is_unit_helper' => 'El producte es ven per unitats (preu unitari)',
    'stock_quantity' => 'Quantitat en Estoc',
    'stock_quantity_helper' => 'Deixar buit per estoc il·limitat',
    'stock' => 'Estoc',
    
    // Traduccions
    'language' => 'Idioma',
    'translation' => 'Traducció',
    
    // Notificacions
    'activated_title' => 'Producte Activat',
    'activated_body' => 'El producte ":name" ha estat activat correctament.',
    'deactivated_title' => 'Producte Desactivat',
    'deactivated_body' => 'El producte ":name" ha estat desactivat correctament.',
    
    'available_title' => 'Producte Disponible',
    'available_body' => 'El producte ":name" està disponible per comandes.',
    'unavailable_title' => 'Producte No Disponible',
    'unavailable_body' => 'El producte ":name" no està disponible temporalment.',
    
    'marked_as_new_title' => 'Marcat com a Novetat',
    'marked_as_new_body' => 'El producte ":name" es mostra com a novetat.',
    'unmarked_as_new_title' => 'Desmarcat com a Novetat',
    'unmarked_as_new_body' => 'El producte ":name" ja no es mostra com a novetat.',
];
