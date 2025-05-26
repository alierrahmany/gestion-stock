<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['Claviers', 'Claviers d\'ordinateur de tous types'],
            ['Souris', 'Souris d\'ordinateur et dispositifs de pointage'],
            ['Écrans', 'Moniteurs et écrans d\'ordinateur'],
            ['Casques', 'Casques audio avec microphones'],
            ['Webcams', 'Caméras pour visioconférence'],
            ['Microphones', 'Microphones autonomes pour ordinateurs'],
            ['Haut-parleurs', 'Systèmes de haut-parleurs pour ordinateur'],
            ['Câbles', 'Divers câbles et connecteurs pour ordinateur'],
            ['Stations d\'accueil', 'Solutions d\'accueil pour portable'],
            ['Adaptateurs', 'Divers adaptateurs pour ordinateur'],
            ['Hubs USB', 'Dispositifs d\'extension USB'],
            ['Stockage externe', 'Disques durs et SSD externes'],
            ['Équipement réseau', 'Routeurs, switchs, etc.'],
            ['Commutateurs KVM', 'Commutateurs clavier-vidéo-souris'],
            ['Supports pour portable', 'Supports ergonomiques pour portable'],
            ['Bras pour écran', 'Supports réglables pour moniteur'],
            ['Tapis de souris', 'Surfaces de bureau pour souris'],
            ['Kits de nettoyage', 'Produits d\'entretien pour matériel'],
            ['Parafoudres', 'Dispositifs de protection électrique'],
            ['Onduleurs', 'Alimentations sans interruption'],
            ['Tablettes graphiques', 'Dispositifs de dessin numérique'],
            ['Lecteurs de codes-barres', 'Dispositifs de scan pour inventaire'],
            ['Télécommandes de présentation', 'Outils de navigation pour diapositives'],
            ['Racks serveur', 'Racks de montage pour équipement'],
            ['Gestion de câbles', 'Solutions d\'organisation des câbles'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category[0],
                'description' => $category[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
