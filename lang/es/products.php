<?php

return [
    'title' => 'Productos',
    'singular' => 'Producto',
    
    // Tabs
    'tab_basic' => 'Información Básica',
    'tab_images' => 'Imágenes',
    'tab_categories' => 'Categorías y Alérgenos',
    'tab_variants' => 'Variantes',
    'tab_config' => 'Configuración',
    
    // Campos básicos
    'name' => 'Nombre',
    'description' => 'Descripción',
    'ingredients' => 'Ingredientes',
    'ingredients_helper' => 'Lista de ingredientes del plato',
    'price' => 'Precio',
    'price_helper' => 'Precio base del producto (sin variantes)',
    'preparation_time' => 'Tiempo de Preparación',
    'preparation_time_helper' => 'Tiempo aproximado de preparación en minutos',
    'minutes' => 'minutos',
    'tax_rate' => 'Tasa de Impuesto (IVA)',
    'tax_rate_helper' => 'Porcentaje de IVA aplicable (ej: 10 para 10%)',
    'sort_order' => 'Orden',
    'sort_order_helper' => 'Número para ordenar el producto en la carta',
    
    // Imágenes
    'photo' => 'Foto',
    'photo1' => 'Foto Principal',
    'photo2' => 'Foto Secundaria',
    'photo_helper' => 'Tamaño máximo: 1MB. Formatos: JPG, PNG',
    
    // Categorías y alérgenos
    'dish_types' => 'Categorías',
    'dish_types_helper' => 'Selecciona las categorías donde aparecerá este producto',
    'allergens' => 'Alérgenos',
    'allergens_helper' => 'Selecciona los alérgenos que contiene este producto',
    'allergens_count' => 'Alérgenos',
    
    // Variantes
    'variants' => 'Variantes',
    'variants_description' => 'Las variantes permiten ofrecer el mismo producto en diferentes tamaños o presentaciones (ej: Media Ración, Ración Completa)',
    'variant_name' => 'Nombre de la Variante',
    'variant_price' => 'Precio',
    'variant_default' => 'Por Defecto',
    'variant_default_helper' => 'Marcar como opción predeterminada',
    
    // Configuración
    'is_active' => 'Activo',
    'is_active_helper' => 'El producto aparece en la carta',
    'is_available' => 'Disponible',
    'is_available_helper' => 'El producto está disponible para pedidos (puede estar activo pero no disponible por falta de stock)',
    'is_new' => 'Novedad',
    'is_new_helper' => 'Marcar como novedad para destacarlo en la carta',
    'is_unit' => 'Por Unidades',
    'is_unit_helper' => 'El producto se vende por unidades (precio unitario)',
    'stock_quantity' => 'Cantidad en Stock',
    'stock_quantity_helper' => 'Dejar vacío para stock ilimitado',
    'stock' => 'Stock',
    
    // Traducciones
    'language' => 'Idioma',
    'translation' => 'Traducción',
    
    // Notificaciones
    'activated_title' => 'Producto Activado',
    'activated_body' => 'El producto ":name" ha sido activado correctamente.',
    'deactivated_title' => 'Producto Desactivado',
    'deactivated_body' => 'El producto ":name" ha sido desactivado correctamente.',
    
    'available_title' => 'Producto Disponible',
    'available_body' => 'El producto ":name" está disponible para pedidos.',
    'unavailable_title' => 'Producto No Disponible',
    'unavailable_body' => 'El producto ":name" no está disponible temporalmente.',
    
    'marked_as_new_title' => 'Marcado como Novedad',
    'marked_as_new_body' => 'El producto ":name" se muestra como novedad.',
    'unmarked_as_new_title' => 'Desmarcado como Novedad',
    'unmarked_as_new_body' => 'El producto ":name" ya no se muestra como novedad.',
];
