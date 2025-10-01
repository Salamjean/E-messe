<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel des nouveaux seeders pour les villes et les communes.
        // Il est recommandé de les exécuter en premier pour que les
        // tables géographiques soient prêtes pour d'éventuelles liaisons.
        $this->call([
            VilleSeeder::class,
            CommuneSeeder::class,
        ]);

        // La création de l'utilisateur de test est conservée.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Si vous souhaitez créer plus d'utilisateurs, vous pouvez décommenter la ligne ci-dessous.
        // User::factory(10)->create();
    }
}