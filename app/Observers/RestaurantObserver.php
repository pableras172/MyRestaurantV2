<?php

namespace App\Observers;

use App\Mail\RestaurantCredentialsMail;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RestaurantObserver
{
    /**
     * Handle the Restaurant "created" event.
     */
    public function created(Restaurant $restaurant): void
    {
        // Generar credenciales
        $cleanSlug = preg_replace('/[^a-zA-Z0-9]/', '', $restaurant->slug);
        $email = 'admin@' . strtolower($cleanSlug);
        $name = ucfirst(str_replace(['-', '_'], ' ', $restaurant->slug));
        $password = Str::random(12);

        // Crear usuario administrador
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => true,
            'is_super_admin' => false,
        ]);

        // Vincular usuario al restaurante como propietario
        $restaurant->users()->attach($user->id, [
            'is_owner' => true,
            'role' => 'admin',
        ]);

        // Enviar correo con credenciales
        try {
            Mail::to($restaurant->email)->send(
                new RestaurantCredentialsMail($restaurant, $email, $password)
            );
        } catch (\Exception $e) {
            \Log::error('Error al enviar correo de credenciales: ' . $e->getMessage());
        }
    }
}
