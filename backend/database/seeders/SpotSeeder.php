<?php

namespace Database\Seeders;

use App\Models\Spot;
use App\Models\Floor;
use App\Models\Parking;
use Illuminate\Database\Seeder;

class SpotSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $parkings = Parking::with('floors')->get();
        $spotCount = 0;

        foreach ($parkings as $parking) {
            // Détermine le nombre de places par étage selon le parking
            $spotsPerFloor = $this->determineSpotsPerFloor($parking->name);

            foreach ($parking->floors as $floor) {
                // Ajuste le nombre de places selon l'étage
                // Les étages supérieurs ont généralement moins de places
                $floorCapacity = $this->adjustForFloorLevel($spotsPerFloor, $floor->number);

                for ($i = 1; $i <= $floorCapacity; $i++) {
                    Spot::create([
                        'parking_id' => $parking->id,
                        'floor_id' => $floor->id,
                        'spot_number' => $i,
                        'occupied' => rand(0, 100) < 30, // 30% occupé en moyenne
                    ]);
                    $spotCount++;
                }
            }
        }

        $this->command->info('✓ ' . $spotCount . ' places de stationnement créées avec succès');
    }

    /**
     * Détermine le nombre de places par étage selon le nom du parking
     */
    private function determineSpotsPerFloor(string $parkingName): int
    {
        // Très grands parkings (200+ places par étage)
        $veryLargeParking = [
            'Parking Riponne',          // 840 places total
            'P+R Vennes',                // 1132 places total
            'Parking de Beaulieu',
        ];

        // Grands parkings (100-150 places par étage)
        $largeParking = [
            'Parking Saint-François',    // 192 places total
            'Parking Centre Flon',
            'P+R Bourdonnette',
            'P+R Ouchy-Olympique',
        ];

        // Parkings moyens (50-100 places par étage)
        $mediumParking = [
            'Parking Rôtillon',          // 180 places total
            'Parking Alpha-Palmiers',
            'Parking Bellefontaine',
            'Parking Chauderon',
            'P+R Blécherette',
            'P+R Bellevaux',
        ];

        // Petits parkings ou parkings de surface (30-50 places)
        if (in_array($parkingName, $veryLargeParking)) {
            return rand(200, 250);
        } elseif (in_array($parkingName, $largeParking)) {
            return rand(100, 150);
        } elseif (in_array($parkingName, $mediumParking)) {
            return rand(50, 100);
        } else {
            return rand(30, 60);
        }
    }

    /**
     * Ajuste la capacité selon le niveau de l'étage
     */
    private function adjustForFloorLevel(int $baseCapacity, int $floorNumber): int
    {
        // Les étages inférieurs (sous-sol profond) ont souvent moins de places
        if ($floorNumber <= -4) {
            return (int) ($baseCapacity * 0.75);
        } elseif ($floorNumber <= -2) {
            return (int) ($baseCapacity * 0.90);
        } else {
            return $baseCapacity;
        }
    }
}
