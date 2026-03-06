<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'address_line_1',
        'address_line_2',
        'phone_1',
        'phone_2',
        'email',
        'website_url',
        'facebook_url',
        'instagram_url',
        'google_profile_url',
        'logo',
        'header_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        'meta_url',
        'is_active',
        'theme_color',
        'menu_style',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'is_owner')
            ->withTimestamps();
    }

    /**
     * Obtiene solo los propietarios del restaurante
     */
    public function owners(): BelongsToMany
    {
        return $this->users()->wherePivot('is_owner', true);
    }

    /**
     * Obtiene el nombre del tenant para Filament
     */
    public function getTenantName(): string
    {
        return $this->name;
    }

    /**
     * Obtiene el identificador único del tenant (slug para subdominios)
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Obtiene la URL del avatar para el selector de tenant en Filament
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
