<?php

return [
    'title' => 'Products',
    'singular' => 'Product',
    
    // Tabs
    'tab_basic' => 'Basic Information',
    'tab_images' => 'Images',
    'tab_categories' => 'Categories & Allergens',
    'tab_variants' => 'Variants',
    'tab_config' => 'Configuration',
    
    // Basic fields
    'name' => 'Name',
    'description' => 'Description',
    'ingredients' => 'Ingredients',
    'ingredients_helper' => 'List of dish ingredients',
    'price' => 'Price',
    'price_helper' => 'Base price of the product (without variants)',
    'preparation_time' => 'Preparation Time',
    'preparation_time_helper' => 'Approximate preparation time in minutes',
    'minutes' => 'minutes',
    'tax_rate' => 'Tax Rate (VAT)',
    'tax_rate_helper' => 'Applicable VAT percentage (e.g., 10 for 10%)',
    'sort_order' => 'Sort Order',
    'sort_order_helper' => 'Number to sort the product on the menu',
    
    // Images
    'photo' => 'Photo',
    'photo1' => 'Main Photo',
    'photo2' => 'Secondary Photo',
    'photo_helper' => 'Maximum size: 1MB. Formats: JPG, PNG',
    
    // Categories and allergens
    'dish_types' => 'Categories',
    'dish_types_helper' => 'Select the categories where this product will appear',
    'allergens' => 'Allergens',
    'allergens_helper' => 'Select the allergens this product contains',
    'allergens_count' => 'Allergens',
    
    // Variants
    'variants' => 'Variants',
    'variants_description' => 'Variants allow offering the same product in different sizes or presentations (e.g., Half Portion, Full Portion)',
    'variant_name' => 'Variant Name',
    'variant_price' => 'Price',
    'variant_default' => 'Default',
    'variant_default_helper' => 'Mark as default option',
    
    // Configuration
    'is_active' => 'Active',
    'is_active_helper' => 'The product appears on the menu',
    'is_available' => 'Available',
    'is_available_helper' => 'The product is available for orders (can be active but not available due to lack of stock)',
    'is_new' => 'New',
    'is_new_helper' => 'Mark as new to highlight it on the menu',
    'is_unit' => 'By Units',
    'is_unit_helper' => 'The product is sold by units (unit price)',
    'stock_quantity' => 'Stock Quantity',
    'stock_quantity_helper' => 'Leave empty for unlimited stock',
    'stock' => 'Stock',
    
    // Translations
    'language' => 'Language',
    'translation' => 'Translation',
    
    // Notifications
    'activated_title' => 'Product Activated',
    'activated_body' => 'The product ":name" has been activated successfully.',
    'deactivated_title' => 'Product Deactivated',
    'deactivated_body' => 'The product ":name" has been deactivated successfully.',
    
    'available_title' => 'Product Available',
    'available_body' => 'The product ":name" is available for orders.',
    'unavailable_title' => 'Product Unavailable',
    'unavailable_body' => 'The product ":name" is temporarily unavailable.',
    
    'marked_as_new_title' => 'Marked as New',
    'marked_as_new_body' => 'The product ":name" is shown as new.',
    'unmarked_as_new_title' => 'Unmarked as New',
    'unmarked_as_new_body' => 'The product ":name" is no longer shown as new.',
];
