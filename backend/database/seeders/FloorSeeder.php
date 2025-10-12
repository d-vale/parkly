<?php

namespace Database\Seeders;

use App\Models\Floor;
use App\Models\Parking;
use Illuminate\Database\Seeder;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $parkings = Parking::all();
        $floorCount = 0;

        foreach ($parkings as $parking) {
            // Détermine le nombre d'étages selon le type et la taille du parking
            $numFloors = $this->determineFloorCount($parking->name);

            for ($i = -$numFloors + 1; $i <= 0; $i++) {
                Floor::create([
                    'number' => $i,
                    'parking_id' => $parking->id,
                ]);
                $floorCount++;
            }
        }

        $this->command->info('✓ ' . $floorCount . ' étages créés avec succès');
    }

    /**
     * Détermine le nombre d'étages selon le nom du parking
     */
    private function determineFloorCount(string $parkingName): int
    {
        // Grands parkings du centre-ville (5-6 étages)
        $largeParking = [
            'Parking Riponne',
            'Parking Saint-François',
            'P+R Vennes',
            'Parking Centre Flon',
            'Parking de Beaulieu',
        ];

        // Parkings moyens (3-4 étages)
        $mediumParking = [
            'Parking Rôtillon',
            'Parking Alpha-Palmiers',
            'Parking Bellefontaine',
            'Parking Chauderon',
            'Parking Montbenon',
            'P+R Bourdonnette',
            'P+Rail Yverdon Gare',
        ];

        // Parkings de surface ou petits (1-2 niveaux)
        // Tous les autres

        if (in_array($parkingName, $largeParking)) {
            return rand(5, 6);
        } elseif (in_array($parkingName, $mediumParking)) {
            return rand(3, 4);
        } else {
            return rand(1, 2);
        }
    }
}
