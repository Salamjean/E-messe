<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommuneSeeder extends Seeder {
    public function run(): void {
        DB::table('communes')->insert([
            // Abidjan (ville_id = 1)
            ['ville_id' => 1, 'nom_commune' => 'Cocody'],
            ['ville_id' => 1, 'nom_commune' => 'Plateau'],
            ['ville_id' => 1, 'nom_commune' => 'Yopougon'],
            ['ville_id' => 1, 'nom_commune' => 'Abobo'],
            ['ville_id' => 1, 'nom_commune' => 'Treichville'],
            ['ville_id' => 1, 'nom_commune' => 'Marcory'],
            ['ville_id' => 1, 'nom_commune' => 'Koumassi'],
            ['ville_id' => 1, 'nom_commune' => 'Port-Bouët'],
            ['ville_id' => 1, 'nom_commune' => 'Adjame'],
            ['ville_id' => 1, 'nom_commune' => 'Songon'],
            ['ville_id' => 1, 'nom_commune' => 'Bingerville'],

            // Yamoussoukro (ville_id = 2)
            ['ville_id' => 2, 'nom_commune' => 'Attiégouakro'],
            ['ville_id' => 2, 'nom_commune' => 'Yamoussoukro-Centre'],

            // Bouaké (ville_id = 3)
            ['ville_id' => 3, 'nom_commune' => 'Belleville'],
            ['ville_id' => 3, 'nom_commune' => 'Koko'],
            ['ville_id' => 3, 'nom_commune' => 'Nimbo'],

            // Daloa (ville_id = 4)
            ['ville_id' => 4, 'nom_commune' => 'Abattoir'],
            ['ville_id' => 4, 'nom_commune' => 'Lobia'],

            // San Pedro (ville_id = 5)
            ['ville_id' => 5, 'nom_commune' => 'Sewe'],
            ['ville_id' => 5, 'nom_commune' => 'Bardo'],

            // Korhogo (ville_id = 6)
            ['ville_id' => 6, 'nom_commune' => 'Haoussabougou'],
            ['ville_id' => 6, 'nom_commune' => 'Soba'],

            // Man (ville_id = 7)
            ['ville_id' => 7, 'nom_commune' => 'Zagne'],
            ['ville_id' => 7, 'nom_commune' => 'Tonon'],

            // Gagnoa (ville_id = 8)
            ['ville_id' => 8, 'nom_commune' => 'Garahio'],
            ['ville_id' => 8, 'nom_commune' => 'Dioulabougou'],

            // Abengourou (ville_id = 9)
            ['ville_id' => 9, 'nom_commune' => 'Agnibilekro'],
            ['ville_id' => 9, 'nom_commune' => 'Zaranou'],

            // Divo (ville_id = 10)
            ['ville_id' => 10, 'nom_commune' => 'Boudoukou'],
            ['ville_id' => 10, 'nom_commune' => 'Guitry'],

            // Odienné (ville_id = 11)
            ['ville_id' => 11, 'nom_commune' => 'Bako'],
            ['ville_id' => 11, 'nom_commune' => 'Tiémé'],

            // Ferkessédougou (ville_id = 12)
            ['ville_id' => 12, 'nom_commune' => 'Koumbala'],
            ['ville_id' => 12, 'nom_commune' => 'Togoniéré'],

            // Bondoukou (ville_id = 13)
            ['ville_id' => 13, 'nom_commune' => 'Sorobango'],
            ['ville_id' => 13, 'nom_commune' => 'Apprompronou'],

            // Soubré (ville_id = 14)
            ['ville_id' => 14, 'nom_commune' => 'Okrouyo'],
            ['ville_id' => 14, 'nom_commune' => 'Grand-Zattry'],

            // Séguéla (ville_id = 15)
            ['ville_id' => 15, 'nom_commune' => 'Kamalo'],
            ['ville_id' => 15, 'nom_commune' => 'Worofla'],

            // Anyama (ville_id = 16)
            ['ville_id' => 16, 'nom_commune' => 'Akoupé-Zeudji'],
            ['ville_id' => 16, 'nom_commune' => 'M’Bonoua'],

            // Bingerville (ville_id = 17)
            ['ville_id' => 17, 'nom_commune' => 'Eloka'],
            ['ville_id' => 17, 'nom_commune' => 'Adjamé-Bingerville'],

            // Grand-Bassam (ville_id = 18)
            ['ville_id' => 18, 'nom_commune' => 'France'],
            ['ville_id' => 18, 'nom_commune' => 'Imperial'],

            // Adzopé (ville_id = 19)
            ['ville_id' => 19, 'nom_commune' => 'Annépé'],
            ['ville_id' => 19, 'nom_commune' => 'Yakassé-Mé'],

            // Agboville (ville_id = 20)
            ['ville_id' => 20, 'nom_commune' => 'Guessiguié'],
            ['ville_id' => 20, 'nom_commune' => 'Grand-Morié'],
        ]);
    }
}
