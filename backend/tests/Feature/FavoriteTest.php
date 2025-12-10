<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Parking;
use App\Models\Owner;
use App\Models\Schedule;
use App\Models\Price;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Parking $parking;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur de test
        $this->user = User::factory()->create();

        // Créer les dépendances pour le parking
        $owner = Owner::create([
            'company_name' => 'Test Company',
            'phone' => '0123456789',
            'email' => 'contact@test.com',
        ]);

        $schedule = Schedule::create([
            'schedule_type' => '24/7',
        ]);

        $price = Price::create([
            'price' => 2.50,
            'minutes' => 60,
        ]);

        // Créer un parking de test
        $this->parking = Parking::create([
            'name' => 'Test Parking',
            'owner_id' => $owner->id,
            'schedule_id' => $schedule->id,
            'price_id' => $price->id,
            'city' => 'Test City',
            'postal_code' => 1000,
            'address' => '123 Test Street',
            'type' => 'Paid',
        ]);
    }

    /**
     * Test: Un utilisateur peut récupérer sa liste de favoris (vide au départ)
     */
    public function test_user_can_get_empty_favorites_list(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/user/favorites');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [],
                'count' => 0,
            ]);
    }

    /**
     * Test: Un utilisateur peut ajouter un parking à ses favoris
     */
    public function test_user_can_add_parking_to_favorites(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/user/favorites/{$this->parking->id}");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Parking ajouté aux favoris avec succès',
                'data' => [
                    'parking_id' => $this->parking->id,
                    'parking_name' => 'Test Parking',
                ],
            ]);

        // Vérifier dans la base de données
        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'parking_id' => $this->parking->id,
        ]);
    }

    /**
     * Test: Un utilisateur ne peut pas ajouter deux fois le même parking
     */
    public function test_user_cannot_add_duplicate_favorite(): void
    {
        // Ajouter une première fois
        $this->user->favorites()->attach($this->parking->id);

        // Essayer d'ajouter une seconde fois
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/user/favorites/{$this->parking->id}");

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Ce parking est déjà dans vos favoris',
            ]);
    }

    /**
     * Test: L'ajout d'un parking inexistant retourne une erreur 404
     */
    public function test_adding_nonexistent_parking_returns_404(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/user/favorites/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Parking introuvable',
            ]);
    }

    /**
     * Test: Un utilisateur peut récupérer sa liste de favoris avec des parkings
     */
    public function test_user_can_get_favorites_list_with_parkings(): void
    {
        // Ajouter le parking aux favoris
        $this->user->favorites()->attach($this->parking->id);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/user/favorites');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'count' => 1,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'city',
                        'postal_code',
                        'address',
                        'type',
                        'owner',
                        'schedule',
                        'price',
                        'added_at',
                    ],
                ],
                'count',
            ]);
    }

    /**
     * Test: Un utilisateur peut retirer un parking de ses favoris
     */
    public function test_user_can_remove_parking_from_favorites(): void
    {
        // Ajouter le parking aux favoris
        $this->user->favorites()->attach($this->parking->id);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/user/favorites/{$this->parking->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Parking retiré des favoris avec succès',
            ]);

        // Vérifier que la relation a été supprimée
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'parking_id' => $this->parking->id,
        ]);
    }

    /**
     * Test: Retirer un parking qui n'est pas dans les favoris retourne une erreur
     */
    public function test_removing_non_favorite_parking_returns_404(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/user/favorites/{$this->parking->id}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Ce parking n\'est pas dans vos favoris',
            ]);
    }

    /**
     * Test: Un utilisateur non authentifié ne peut pas accéder aux favoris
     */
    public function test_unauthenticated_user_cannot_access_favorites(): void
    {
        $response = $this->getJson('/api/user/favorites');
        $response->assertStatus(401);

        $response = $this->postJson("/api/user/favorites/{$this->parking->id}");
        $response->assertStatus(401);

        $response = $this->deleteJson("/api/user/favorites/{$this->parking->id}");
        $response->assertStatus(401);
    }

    /**
     * Test: Les favoris sont isolés par utilisateur
     */
    public function test_favorites_are_isolated_per_user(): void
    {
        $otherUser = User::factory()->create();

        // User 1 ajoute un favori
        $this->user->favorites()->attach($this->parking->id);

        // User 2 ne devrait pas voir ce favori
        $response = $this->actingAs($otherUser, 'sanctum')
            ->getJson('/api/user/favorites');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'count' => 0,
            ]);
    }
}
