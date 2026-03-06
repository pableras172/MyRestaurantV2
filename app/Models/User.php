<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'is_super_admin',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relación con restaurantes
     */
    public function restaurants(): BelongsToMany
    {
        return $this->belongsToMany(Restaurant::class)
            ->withPivot('role', 'is_owner')
            ->withTimestamps();
    }

    /**
     * Verifica si el usuario es super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    /**
     * Verifica si el usuario es owner de algún restaurante
     */
    public function isOwnerOf(Restaurant $restaurant): bool
    {
        return $this->restaurants()
            ->wherePivot('restaurant_id', $restaurant->id)
            ->wherePivot('is_owner', true)
            ->exists();
    }

    /**
     * Obtiene el rol del usuario en un restaurante específico
     */
    public function getRoleInRestaurant(Restaurant $restaurant): ?string
    {
        $pivot = $this->restaurants()
            ->wherePivot('restaurant_id', $restaurant->id)
            ->first()?->pivot;

        return $pivot?->role;
    }

    /**
     * Verifica si el usuario es owner o admin en algún restaurante
     */
    public function isOwnerOrAdmin(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->restaurants()
            ->wherePivot('is_owner', true)
            ->exists() || 
            $this->restaurants()
            ->wherePivot('role', 'admin')
            ->exists();
    }

    /**
     * Verifica si el usuario es manager
     */
    public function isManager(): bool
    {
        return $this->restaurants()
            ->wherePivot('role', 'manager')
            ->exists();
    }

    /**
     * Verifica si el usuario es empleado (sin permisos administrativos)
     */
    public function isEmployee(): bool
    {
        return $this->restaurants()
            ->whereIn('restaurant_user.role', ['employee', 'waiter', 'chef'])
            ->exists();
    }

    /**
     * Puede acceder al panel de Filament
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active;
    }

    /**
     * Obtiene los tenants (restaurantes) del usuario
     */
    public function getTenants(Panel $panel): Collection
    {
        // Si es super admin, tiene acceso a todos los restaurantes
        if ($this->isSuperAdmin()) {
            return Restaurant::where('is_active', true)->get();
        }

        // Si no, solo a sus restaurantes asignados
        return $this->restaurants()->where('is_active', true)->get();
    }

    /**
     * Obtiene los restaurantes a los que puede cambiar
     */
    public function canAccessTenant(Model $tenant): bool
    {
        // Super admin puede acceder a cualquier tenant
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Verifica si el usuario pertenece al restaurante
        return $this->restaurants()->where('restaurant_id', $tenant->id)->exists();
    }
}
