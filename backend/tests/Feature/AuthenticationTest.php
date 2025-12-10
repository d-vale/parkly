<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Un utilisateur peut s'inscrire avec des données valides
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                ],
            ])
            ->assertJson([
                'success' => true,
                'user' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                ],
            ]);

        // Vérifier que l'utilisateur a été créé dans la base de données
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        // Vérifier que le mot de passe est bien hashé
        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test: L'inscription échoue avec des données invalides
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/register', [
            'first_name' => '',
            'last_name' => 'Doe',
            'email' => 'invalid-email',
            'password' => '123', // Trop court
            'password_confirmation' => '456', // Ne correspond pas
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'email', 'password']);
    }

    /**
     * Test: L'inscription échoue avec un email déjà utilisé
     */
    public function test_registration_fails_with_duplicate_email(): void
    {
        // Créer un utilisateur existant
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/register', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Un utilisateur peut se connecter avec des identifiants valides
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        // Créer un utilisateur
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                ],
            ])
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'email' => 'john@example.com',
                ],
            ]);
    }

    /**
     * Test: La connexion échoue avec des identifiants invalides
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        // Créer un utilisateur
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Un utilisateur authentifié peut récupérer ses informations
     */
    public function test_authenticated_user_can_get_their_info(): void
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john@example.com',
                ],
            ]);
    }

    /**
     * Test: Un utilisateur non authentifié ne peut pas accéder à /api/user
     */
    public function test_unauthenticated_user_cannot_access_user_endpoint(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /**
     * Test: Un utilisateur peut se déconnecter
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Déconnexion réussie',
            ]);
    }

    /**
     * Test: Le endpoint /api/check retourne le statut d'authentification
     */
    public function test_check_endpoint_returns_authentication_status(): void
    {
        // Non authentifié
        $response = $this->getJson('/api/check');
        $response->assertStatus(200)
            ->assertJson([
                'authenticated' => false,
                'user' => null,
            ]);

        // Authentifié
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/check');

        $response->assertStatus(200)
            ->assertJson([
                'authenticated' => true,
            ])
            ->assertJsonStructure([
                'authenticated',
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                ],
            ]);
    }
}
