<?php

namespace Tests\Feature;

use App\Models\Knife;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KnifeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function it_can_view_knife_index()
    {
        $response = $this->get(route('knives.index'));

        $response->assertStatus(200)
            ->assertViewIs('Knives.Index');
    }

    public function it_can_view_knife_panel()
    {
        $response = $this->get(route('knives.panel'));

        $response->assertStatus(200)
            ->assertViewIs('Knives.knifePanel');
    }

    public function it_can_create_a_knife()
    {
        $user = \App\Models\User::factory()->create(); // Create a user to authenticate
        $this->actingAs($user); // Authenticate the user

        // Mock the file storage
        Storage::fake('public');

        $data = [
            'name' => 'Test Knife',
            'description' => 'A sharp knife.',
            'price' => 100,
            'image' => \Illuminate\Http\UploadedFile::fake()->image('knife.jpg'),
        ];

        $response = $this->post(route('knives.store'), $data);

        $response->assertStatus(200)
            ->assertSessionHas('message', 'Knife created successfully');

        $this->assertDatabaseHas('knives', [
            'name' => 'Test Knife',
            'price' => 100,
        ]);

        // Assert that the image was stored
        Storage::disk('public')->assertExists('knives/' . $data['image']->hashName());
    }

    public function it_can_update_a_knife()
    {
        $user = \App\Models\User::factory()->create(); // Create a user to authenticate
        $this->actingAs($user); // Authenticate the user

        $knife = Knife::factory()->create(); // Create a knife to update

        $data = [
            'name' => 'Updated Knife',
            'description' => 'An updated knife.',
            'price' => 150,
            'image' => \Illuminate\Http\UploadedFile::fake()->image('updated_knife.jpg'),
        ];

        $response = $this->put(route('knives.update', $knife->id), $data);

        $response->assertStatus(200)
            ->assertSessionHas('message', 'Knife updated successfully');

        $this->assertDatabaseHas('knives', [
            'name' => 'Updated Knife',
            'price' => 150,
        ]);
    }

    public function it_can_delete_a_knife()
    {
        $user = \App\Models\User::factory()->create(); // Create a user to authenticate
        $this->actingAs($user); // Authenticate the user

        $knife = Knife::factory()->create(); // Create a knife to delete

        $response = $this->delete(route('knives.destroy', $knife->id));

        $response->assertStatus(200)
            ->assertSessionHas('message', 'Knife deleted successfully');

        $this->assertDatabaseMissing('knives', [
            'id' => $knife->id,
        ]);
    }

    public function it_requires_authentication_to_access_methods()
    {
        // Test that unauthenticated users cannot access the knife-related methods
        $response = $this->get(route('knives.index'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('knives.store'));
        $response->assertRedirect(route('login'));

        $response = $this->put(route('knives.update', 1));
        $response->assertRedirect(route('login'));

        $response = $this->delete(route('knives.destroy', 1));
        $response->assertRedirect(route('login'));
    }
}
