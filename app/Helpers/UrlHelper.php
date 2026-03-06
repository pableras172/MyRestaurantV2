<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * Genera una URL para el menú público según el entorno
     */
    public static function menuUrl($restaurant, array $params = []): string
    {
        // En producción usar subdominio
        if (config('app.env') === 'production' && config('app.domain')) {
            $url = (request()->secure() ? 'https://' : 'http://') . 
                   $restaurant->slug . '.' . config('app.domain');
            
            // Agregar parámetros de query string
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }
            
            return $url;
        }
        
        // En desarrollo usar slug en URL
        return route('public.menu.index', array_merge(['restaurant' => $restaurant->slug], $params));
    }

    /**
     * Genera una URL para una categoría según el entorno
     */
    public static function categoryUrl($restaurant, $categoryId): string
    {
        if (config('app.env') === 'production' && config('app.domain')) {
            return (request()->secure() ? 'https://' : 'http://') . 
                   $restaurant->slug . '.' . config('app.domain') . 
                   '/category/' . $categoryId;
        }
        
        return route('public.menu.category', ['restaurant' => $restaurant->slug, 'category' => $categoryId]);
    }

    /**
     * Genera una URL para un producto según el entorno
     */
    public static function productUrl($restaurant, $productId): string
    {
        if (config('app.env') === 'production' && config('app.domain')) {
            return (request()->secure() ? 'https://' : 'http://') . 
                   $restaurant->slug . '.' . config('app.domain') . 
                   '/product/' . $productId;
        }
        
        return route('public.menu.product', ['restaurant' => $restaurant->slug, 'product' => $productId]);
    }
}
