<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VilleSeeder extends Seeder {
    public function run(): void {
        DB::table('villes')->insert([
            ['nom_ville' => 'Abidjan'],
            ['nom_ville' => 'Yamoussoukro'],
            ['nom_ville' => 'Bouaké'],
            ['nom_ville' => 'Daloa'],
            ['nom_ville' => 'San Pedro'],
            ['nom_ville' => 'Korhogo'],
            ['nom_ville' => 'Man'],
            ['nom_ville' => 'Gagnoa'],
            ['nom_ville' => 'Abengourou'],
            ['nom_ville' => 'Divo'],
            ['nom_ville' => 'Odienné'],
            ['nom_ville' => 'Ferkessédougou'],
            ['nom_ville' => 'Bondoukou'],
            ['nom_ville' => 'Soubré'],
            ['nom_ville' => 'Séguéla'],
            ['nom_ville' => 'Anyama'],
            ['nom_ville' => 'Bingerville'],
            ['nom_ville' => 'Grand-Bassam'],
            ['nom_ville' => 'Adzopé'],
            ['nom_ville' => 'Agboville'],
        ]);
    }
}
