<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $owners = [
            [
                'company_name' => 'INOVIL SA',
                'phone' => '+41 21 321 33 00',
                'email' => 'info@inovil.ch',
            ],
            [
                'company_name' => 'Ville de Lausanne',
                'phone' => '+41 21 315 38 00',
                'email' => 'stationnement@lausanne.ch',
            ],
            [
                'company_name' => 'Beau-Rivage Palace SA',
                'phone' => '+41 21 613 33 33',
                'email' => 'info@brp.ch',
            ],
            [
                'company_name' => 'Parking de Beaulieu SA',
                'phone' => '+41 21 641 51 11',
                'email' => 'info@parking-beaulieu.ch',
            ],
            [
                'company_name' => 'Parking Alpha-Palmiers SA',
                'phone' => '+41 21 323 08 23',
                'email' => 'info@parking-alpha.ch',
            ],
            [
                'company_name' => 'Parking Bellefontaine SA',
                'phone' => '+41 21 625 26 18',
                'email' => 'contact@bellefontaine-parking.ch',
            ],
            [
                'company_name' => 'Coop Société Coopérative',
                'phone' => '+41 61 336 66 66',
                'email' => 'service.clientele@coop.ch',
            ],
            [
                'company_name' => 'Ville d\'Yverdon-les-Bains',
                'phone' => '+41 24 423 61 00',
                'email' => 'info@yverdon-les-bains.ch',
            ],
            [
                'company_name' => 'CFF SA',
                'phone' => '+41 51 220 11 11',
                'email' => 'parkings@sbb.ch',
            ],
            [
                'company_name' => 'Y-Parc SA',
                'phone' => '+41 24 423 03 00',
                'email' => 'info@y-parc.ch',
            ],
            [
                'company_name' => 'Port d\'Ouchy SA',
                'phone' => '+41 21 617 01 00',
                'email' => 'info@port-ouchy.ch',
            ],
        ];

        foreach ($owners as $owner) {
            Owner::create($owner);
        }

        $this->command->info('✓ Propriétaires créés avec succès');
    }
}
