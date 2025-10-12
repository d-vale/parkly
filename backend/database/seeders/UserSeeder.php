<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Parking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Dupont',
                'email' => 'jean.dupont@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'Marie',
                'last_name' => 'Martin',
                'email' => 'marie.martin@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'Pierre',
                'last_name' => 'Favre',
                'email' => 'pierre.favre@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Blanc',
                'email' => 'sophie.blanc@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'Luc',
                'last_name' => 'Rossier',
                'email' => 'luc.rossier@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'first_name' => 'Admin',
                'last_name' => 'Parkly',
                'email' => 'admin@parkly.ch',
                'password' => Hash::make('admin123'),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Ajoute quelques parkings favoris aléatoires pour chaque utilisateur
            $randomParkings = Parking::inRandomOrder()->take(rand(2, 5))->pluck('id');
            $user->favoriteParkings()->attach($randomParkings);
        }

        $this->command->info('✓ ' . count($users) . ' utilisateurs créés avec succès');
        $this->command->info('✓ Favoris attribués aux utilisateurs');
        $this->command->info('');
        $this->command->info('===========================================');
        $this->command->info('  Comptes de test créés :');
        $this->command->info('===========================================');
        $this->command->info('  Email: admin@parkly.ch');
        $this->command->info('  Password: admin123');
        $this->command->info('-------------------------------------------');
        $this->command->info('  Email: jean.dupont@example.com');
        $this->command->info('  Password: password');
        $this->command->info('===========================================');
    }
}
