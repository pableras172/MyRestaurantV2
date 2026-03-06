<?php

namespace App\Traits;

use Filament\Facades\Filament;

/**
 * Trait HasTenantStorage
 * 
 * Este trait centraliza la gestión de rutas de almacenamiento organizadas por tenant.
 * Proporciona métodos para obtener paths específicos para cada tipo de archivo,
 * asegurando que los archivos de cada restaurante se almacenen en carpetas separadas.
 * 
 * ESTRUCTURA DE ALMACENAMIENTO:
 * storage/app/public/
 * ├── restaurants/
 * │   ├── bella-italia/          (slug del restaurante)
 * │   │   ├── users/            (fotos de usuarios)
 * │   │   ├── products/         (imágenes de productos)
 * │   │   ├── categories/       (imágenes de categorías)
 * │   │   └── meta/             (imágenes SEO/OpenGraph)
 * │   ├── sushi-master/
 * │   │   ├── users/
 * │   │   ├── products/
 * │   │   └── ...
 * │   └── global/               (sin tenant, fallback)
 * 
 * USO EN FILAMENT FORMS:
 * ```php
 * use App\Traits\HasTenantStorage;
 * 
 * class ProductForm
 * {
 *     use HasTenantStorage;
 *     
 *     public static function configure(Schema $schema): Schema
 *     {
 *         return $schema->components([
 *             FileUpload::make('image')
 *                 ->directory(self::getProductImagesPath())
 *                 ->image(),
 *             
 *             FileUpload::make('photo')
 *                 ->directory(self::getUserPhotosPath())
 *                 ->image(),
 *         ]);
 *     }
 * }
 * ```
 * 
 * USO EN RESOURCES:
 * ```php
 * class ProductResource extends Resource
 * {
 *     use HasTenantStorage;
 *     
 *     // Usar en schemas, tablas, etc.
 * }
 * ```
 * 
 * BENEFICIOS:
 * - Organización clara por restaurante
 * - Fácil backup/migración de archivos por tenant
 * - Aislamiento de datos entre restaurantes
 * - Paths consistentes en toda la aplicación
 * - Cambios centralizados sin modificar múltiples archivos
 * 
 * @package App\Traits
 */
trait HasTenantStorage
{
    /**
     * Obtiene el path de almacenamiento base para el tenant actual
     * 
     * Este es el método principal que usan los demás. Detecta automáticamente
     * el restaurante actual desde el contexto de Filament y construye el path.
     * 
     * Si NO hay tenant activo (ej: super admin sin restaurante seleccionado),
     * usa "global" como carpeta fallback.
     * 
     * @param string $subfolder Subcarpeta opcional (users, products, etc.)
     * @return string Path relativo desde storage/app/public/
     * 
     * @example
     * // Con tenant "bella-italia" y subfolder "products"
     * getTenantStoragePath('products'); 
     * // Retorna: "restaurants/bella-italia/products"
     * 
     * // Sin tenant
     * getTenantStoragePath('products'); 
     * // Retorna: "global/products"
     * 
     * // Con tenant pero sin subfolder
     * getTenantStoragePath(); 
     * // Retorna: "restaurants/bella-italia"
     */
    public static function getTenantStoragePath(string $subfolder = ''): string
    {
        $tenant = Filament::getTenant();
        
        if (!$tenant) {
            return "global/{$subfolder}";
        }
        
        $path = "restaurants/{$tenant->slug}";
        
        if ($subfolder) {
            $path .= "/{$subfolder}";
        }
        
        return $path;
    }
    
    /**
     * Path para logos del restaurante
     * 
     * Usado en el formulario de Restaurant para el campo 'logo'.
     * Los logos se guardan en la raíz de cada restaurante.
     * 
     * @return string Path relativo para logos
     * 
     * @example
     * FileUpload::make('logo')
     *     ->directory(self::getRestaurantLogoPath())
     * 
     * // Genera: "restaurants/bella-italia/logo.png"
     */
    public static function getRestaurantLogoPath(): string
    {
        $tenant = Filament::getTenant();
        return $tenant ? "restaurants/{$tenant->slug}" : 'restaurants/global';
    }
    
    /**
     * Path para fotos de usuarios
     * 
     * Usado en UserForm para el campo 'photo'.
     * Cada restaurante tiene su propia carpeta de usuarios.
     * 
     * @return string Path relativo para fotos de usuarios
     * 
     * @example
     * FileUpload::make('photo')
     *     ->directory(self::getUserPhotosPath())
     * 
     * // Genera: "restaurants/bella-italia/users/juan-perez.jpg"
     */
    public static function getUserPhotosPath(): string
    {
        return static::getTenantStoragePath('users');
    }
    
    /**
     * Path para imágenes de productos
     * 
     * Usado en ProductForm para imágenes de productos del menú.
     * Permite múltiples imágenes por producto (galería).
     * 
     * @return string Path relativo para imágenes de productos
     * 
     * @example
     * FileUpload::make('images')
     *     ->directory(self::getProductImagesPath())
     *     ->multiple()
     * 
     * // Genera: "restaurants/sushi-master/products/salmon-roll.jpg"
     */
    public static function getProductImagesPath(): string
    {
        return static::getTenantStoragePath('products');
    }
    
    /**
     * Path para imágenes de categorías
     * 
     * Usado en CategoryForm para imágenes de categorías del menú
     * (Entrantes, Principales, Postres, etc.).
     * 
     * @return string Path relativo para imágenes de categorías
     * 
     * @example
     * FileUpload::make('image')
     *     ->directory(self::getCategoryImagesPath())
     * 
     * // Genera: "restaurants/el-asador/categories/carnes.jpg"
     */
    public static function getCategoryImagesPath(): string
    {
        return static::getTenantStoragePath('categories');
    }
    
    /**
     * Path para imágenes meta/SEO
     * 
     * Usado para imágenes de Open Graph, Twitter Cards, etc.
     * Típicamente en formularios de Restaurant o páginas especiales.
     * 
     * @return string Path relativo para imágenes meta/SEO
     * 
     * @example
     * FileUpload::make('meta_image')
     *     ->directory(self::getMetaImagesPath())
     * 
     * // Genera: "restaurants/bella-italia/meta/og-image.jpg"
     */
    public static function getMetaImagesPath(): string
    {
        return static::getTenantStoragePath('meta');
    }
}
