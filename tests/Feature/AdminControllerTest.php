<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function it_shows_the_dashboard_for_admins()
    {
        // Create an admin user
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        // Authenticate as admin
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200)
            ->assertViewIs('Admin.Dashboard')
            ->assertSee($admin->name); // Ensure admin's name is present
    }

    public function it_redirects_non_admins_from_the_dashboard()
    {
        // Create a non-admin user
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        // Authenticate as a non-admin user
        $this->actingAs($user);

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect('/');
    }

    public function it_displays_the_edit_profile_form_for_admins()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.profile.edit'));

        $response->assertStatus(200)
            ->assertViewIs('Admin.Profile.Edit');
    }

    public function it_updates_the_admin_profile()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $data = [
            'name' => 'Updated Admin Name',
            'email' => 'updatedadmin@example.com',
        ];

        $response = $this->put(route('admin.profile.update'), $data);

        $response->assertRedirect(route('admin.profile.edit'))
            ->assertSessionHas('status', 'Profile updated successfully!');

        $admin->refresh();
        $this->assertEquals('Updated Admin Name', $admin->name);
        $this->assertEquals('updatedadmin@example.com', $admin->email);
    }

    public function it_deletes_the_admin_account()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $data = ['password' => 'password'];

        // Ensure the password is correct before deletion
        $response = $this->post(route('admin.account.delete'), $data);

        $response->assertRedirect('/')
            ->assertSessionHasNoErrors();

        $this->assertDeleted($admin);
    }

    public function it_can_view_all_users()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200)
            ->assertViewIs('Admin.Users.Index');
    }

    public function it_can_edit_a_user_profile()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create(); // A user to be edited

        $this->actingAs($admin);

        $response = $this->get(route('admin.users.edit', $user->id));

        $response->assertStatus(200)
            ->assertViewIs('Admin.Users.Edit')
            ->assertSee($user->name);
    }

    public function it_updates_a_user_profile()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $this->actingAs($admin);

        $data = [
            'name' => 'Updated User Name',
            'email' => 'updateduser@example.com',
            'is_admin' => false,
        ];

        $response = $this->put(route('admin.users.update', $user->id), $data);

        $response->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('status', 'User updated successfully!');

        $user->refresh();
        $this->assertEquals('Updated User Name', $user->name);
        $this->assertEquals('updateduser@example.com', $user->email);
        $this->assertFalse($user->is_admin);
    }

    public function it_deletes_a_user_account()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create();

        $this->actingAs($admin);

        $response = $this->delete(route('admin.users.delete', $user->id));

        $response->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('status', 'User deleted successfully!');

        $this->assertDeleted($user);
    }

    public function it_prevents_deleting_own_account()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->delete(route('admin.users.delete', $admin->id));

        $response->assertRedirect(route('admin.dashboard'))
            ->assertSessionHasErrors('You cannot delete your own account.');
    }
}
