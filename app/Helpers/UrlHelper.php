<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * Genera una URL para el menú público según el entorno
     */
    public static function menuUrl($restaurant, array $params = []): string
    {
        $domain = config('app.domain');
        
        // Si APP_DOMAIN está configurado, usar subdominios
        if ($domain) {
            $url = (request()->secure() ? 'https://' : 'http://') . 
                   $restaurant->slug . '.' . $domain;
            
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
        $domain = config('app.domain');
        
        if ($domain) {
            return (request()->secure() ? 'https://' : 'http://') . 
                   $restaurant->slug . '.' . $domain . 
                   '/category/' . $categoryId;
        }
        
        return route('public.menu.category', ['restaurant' => $restaurant->slug, 'category' => $categoryId]);
    }

    /**
     * Genera una URL para un producto según el entorno
     */
    public static function productUrl($restaurant, $productId): string
    {
        $domain = config('app.domain');
        
        if ($domain) {
            return (request()->secure() ? 'https://' : 'http://') . 
                   $restaurant->slug . '.' . $domain . 
                   '/product/' . $productId;
        }
        
        return route('public.menu.product', ['restaurant' => $restaurant->slug, 'product' => $productId]);
    }
}
