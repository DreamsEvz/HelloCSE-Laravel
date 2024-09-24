<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Création d'un seul compte admin pour les tests
        Admin::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),,
        ]);

        //Création d'un jeu de test pertinenent notamment pour des profils avec des statuts différents
        Profile::factory(15)->create();
    }
}
