<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (acceso a todos los restaurantes)
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@myrestaurant.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
            'is_active' => true,
        ]);

        // Obtener restaurantes
        $restaurants = Restaurant::all();

        // Crear usuarios para cada restaurante
        foreach ($restaurants as $index => $restaurant) {
            // Owner del restaurante
            $owner = User::create([
                'name' => "Owner {$restaurant->name}",
                'email' => "owner@{$restaurant->slug}.com",
                'password' => Hash::make('password'),
                'is_super_admin' => false,
                'is_active' => true,
            ]);

            // Asignar owner al restaurante
            $restaurant->users()->attach($owner->id, [
                'role' => 'admin',
                'is_owner' => true,
            ]);

            // Manager del restaurante
            $manager = User::create([
                'name' => "Manager {$restaurant->name}",
                'email' => "manager@{$restaurant->slug}.com",
                'password' => Hash::make('password'),
                'is_super_admin' => false,
                'is_active' => true,
            ]);

            $restaurant->users()->attach($manager->id, [
                'role' => 'manager',
                'is_owner' => false,
            ]);

            // Empleado del restaurante
            $employee = User::create([
                'name' => "Employee {$restaurant->name}",
                'email' => "employee@{$restaurant->slug}.com",
                'password' => Hash::make('password'),
                'is_super_admin' => false,
                'is_active' => true,
            ]);

            $restaurant->users()->attach($employee->id, [
                'role' => 'employee',
                'is_owner' => false,
            ]);
        }
    }
}
