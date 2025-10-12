<?php

namespace Database\Seeders;

use App\Models\Parking;
use Illuminate\Database\Seeder;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $parkings = [
            // ========== LAUSANNE - Parkings INOVIL ==========
            [
                'name' => 'Parking Riponne',
                'owner_id' => 1, // INOVIL SA
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1005,
                'address' => 'Place de la Riponne 12',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Saint-François',
                'owner_id' => 1, // INOVIL SA
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Place Saint-François 2',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Rôtillon',
                'owner_id' => 1, // INOVIL SA
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Rue Neuve 9',
                'type' => 'Paid',
            ],

            // ========== LAUSANNE - Parkings Ville de Lausanne (P+R) ==========
            [
                'name' => 'P+R Vennes',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 9, // 16 CHF jour avec transport
                'city' => 'Lausanne',
                'postal_code' => 1010,
                'address' => 'Avenue de la Vallonnette 31',
                'type' => 'Paid',
            ],
            [
                'name' => 'P+R Bourdonnette',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 9, // 16 CHF jour avec transport
                'city' => 'Lausanne',
                'postal_code' => 1018,
                'address' => 'Chemin de la Bourdonnette 87',
                'type' => 'Paid',
            ],
            [
                'name' => 'P+R Ouchy-Olympique',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 9, // 16 CHF jour avec transport
                'city' => 'Lausanne',
                'postal_code' => 1006,
                'address' => 'Avenue de Rhodanie 60',
                'type' => 'Paid',
            ],
            [
                'name' => 'P+R Blécherette',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 9, // 16 CHF jour avec transport
                'city' => 'Lausanne',
                'postal_code' => 1052,
                'address' => 'Route de la Blécherette 155',
                'type' => 'Paid',
            ],
            [
                'name' => 'P+R Bellevaux',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 9, // 16 CHF jour avec transport
                'city' => 'Lausanne',
                'postal_code' => 1007,
                'address' => 'Avenue du Grey 40',
                'type' => 'Paid',
            ],

            // ========== LAUSANNE - Parkings Longue Durée ==========
            [
                'name' => 'Parking Longue Durée Vélodrome',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 4, // 8 CHF jour
                'city' => 'Lausanne',
                'postal_code' => 1000,
                'address' => 'Chemin du Vélodrome',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Longue Durée Samaranch',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 4, // 8 CHF jour
                'city' => 'Lausanne',
                'postal_code' => 1005,
                'address' => 'Avenue Samaranch',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Longue Durée Bossons',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 4, // 8 CHF jour
                'city' => 'Lausanne',
                'postal_code' => 1018,
                'address' => 'Chemin des Bossons 2',
                'type' => 'Paid',
            ],

            // ========== LAUSANNE - Parkings Privés ==========
            [
                'name' => 'Parking Beau-Rivage Palace',
                'owner_id' => 3, // Beau-Rivage Palace SA
                'schedule_id' => 1, // 24/7
                'price_id' => 11, // 3 CHF/h (premium)
                'city' => 'Lausanne',
                'postal_code' => 1006,
                'address' => 'Place du Port 17-19',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking de Beaulieu',
                'owner_id' => 4, // Parking de Beaulieu SA
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1007,
                'address' => 'Avenue des Bergières 10',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Alpha-Palmiers',
                'owner_id' => 5, // Parking Alpha-Palmiers SA
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Avenue du Théâtre 1',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Bellefontaine',
                'owner_id' => 6, // Parking Bellefontaine SA
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Avenue de la Gare 10',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Coop Caroline',
                'owner_id' => 7, // Coop Société Coopérative
                'schedule_id' => 3, // 7am-6pm
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Place de la Caroline 4',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Chauderon',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Place Chauderon 7',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Montbenon',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 1, // 2.50 CHF/h
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Allée Ernest-Ansermet 3',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Centre Flon',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 11, // 3 CHF/h (premium)
                'city' => 'Lausanne',
                'postal_code' => 1003,
                'address' => 'Rue de Genève 17',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Port d\'Ouchy',
                'owner_id' => 11, // Port d'Ouchy SA
                'schedule_id' => 1, // 24/7
                'price_id' => 14, // 2 CHF/h (zone loisirs)
                'city' => 'Lausanne',
                'postal_code' => 1006,
                'address' => 'Place de la Navigation 10',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Stade Pierre-de-Coubertin',
                'owner_id' => 2, // Ville de Lausanne
                'schedule_id' => 1, // 24/7
                'price_id' => 14, // 2 CHF/h (zone loisirs)
                'city' => 'Lausanne',
                'postal_code' => 1007,
                'address' => 'Avenue Pierre-de-Coubertin 20',
                'type' => 'Paid',
            ],

            // ========== YVERDON-LES-BAINS - Parkings Ville ==========
            [
                'name' => 'P+Rail Yverdon Gare',
                'owner_id' => 9, // CFF SA
                'schedule_id' => 1, // 24/7
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Avenue de la Gare 2',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Rue de la Gare',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Rue de la Gare 15',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Avenue des Trois-Lacs',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Avenue des Trois-Lacs 3',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Y-Parc',
                'owner_id' => 10, // Y-Parc SA
                'schedule_id' => 1, // 24/7
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Avenue des Sciences 5',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Centre Sportif des Iles',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Rue des Iles',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking de la Plage',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 8, // 6 CHF jour (zone loisirs)
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Chemin des Grèves de Clendy',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Rives du Lac',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 1, // 24/7
                'price_id' => 8, // 6 CHF jour (zone loisirs)
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Promenade Jean-Jacques-Rousseau',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Explorit',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 1, // 24/7
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Rue Galilée 15',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Avenue Haldimand',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 2, // 6am-7pm
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Avenue Haldimand 63',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Boulodrome',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 1, // 24/7
                'price_id' => 8, // 6 CHF jour (zone loisirs)
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Avenue des Sports 20',
                'type' => 'Paid',
            ],
            [
                'name' => 'Parking Hôpital d\'Yverdon',
                'owner_id' => 8, // Ville d'Yverdon-les-Bains
                'schedule_id' => 1, // 24/7
                'price_id' => 5, // 1.80 CHF/h
                'city' => 'Yverdon-les-Bains',
                'postal_code' => 1400,
                'address' => 'Rue de l\'Hôpital 26',
                'type' => 'Paid',
            ],
        ];

        foreach ($parkings as $parking) {
            Parking::create($parking);
        }

        $this->command->info('✓ ' . count($parkings) . ' parkings créés avec succès');
    }
}
