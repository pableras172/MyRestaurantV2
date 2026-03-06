<?php

use App\Helpers\UrlHelper;

if (!function_exists('menu_url')) {
    function menu_url($restaurant, array $params = []): string
    {
        return UrlHelper::menuUrl($restaurant, $params);
    }
}

if (!function_exists('category_url')) {
    function category_url($restaurant, $categoryId): string
    {
        return UrlHelper::categoryUrl($restaurant, $categoryId);
    }
}

if (!function_exists('product_url')) {
    function product_url($restaurant, $productId): string
    {
        return UrlHelper::productUrl($restaurant, $productId);
    }
}
