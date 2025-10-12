<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $prices = [
            // Tarifs standards Lausanne (2.50 CHF/h)
            ['price' => 2.50, 'minutes' => 60],
            ['price' => 1.25, 'minutes' => 30],
            ['price' => 5.00, 'minutes' => 120],
            ['price' => 8.00, 'minutes' => 480], // Forfait journalier longue durée

            // Tarifs Yverdon (1.80 CHF/h)
            ['price' => 1.80, 'minutes' => 60],
            ['price' => 0.90, 'minutes' => 30],
            ['price' => 3.60, 'minutes' => 120],
            ['price' => 6.00, 'minutes' => 480], // Forfait journalier Yverdon

            // Tarifs P+R avec transport (16 CHF jour)
            ['price' => 16.00, 'minutes' => 1440], // Forfait jour P+R + transport
            ['price' => 125.00, 'minutes' => 43200], // Abonnement mensuel P+R (30j)

            // Tarifs premium centre-ville
            ['price' => 3.00, 'minutes' => 60],
            ['price' => 1.50, 'minutes' => 30],
            ['price' => 6.00, 'minutes' => 120],

            // Tarifs zones loisirs
            ['price' => 2.00, 'minutes' => 60],
            ['price' => 1.00, 'minutes' => 30],
        ];

        foreach ($prices as $price) {
            Price::create($price);
        }

        $this->command->info('✓ Tarifs créés avec succès');
    }
}
