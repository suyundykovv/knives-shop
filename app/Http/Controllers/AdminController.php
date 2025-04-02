<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(Request $request): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'status' => session('status'),
            'users' => User::all(), // Retrieve all users
        ]);
    }

    /**
     * Display the admin's profile form.
     */
    public function editProfile(Request $request): Response
    {
        return Inertia::render('Admin/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the admin's profile information.
     */
    public function updateProfile(AdminUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // If the email has changed, invalidate the email verification status
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('admin.profile.edit');
    }

    /**
     * Delete the admin's account.
     */
    public function destroyAccount(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $admin = $request->user();

        Auth::logout();

        $admin->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * View all users (admin-only).
     */
    public function viewAllUsers(Request $request): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::all(), // Retrieve all users
        ]);
    }

    /**
     * Show the form to edit a user's profile.
     */
    public function editUserProfile($id): Response
    {
        $user = User::findOrFail($id);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update a user's profile information (admin-only).
     */
    public function updateUserProfile(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->fill($request->validated());
        $user->save();

        return Redirect::route('admin.users.viewAll');
    }

    /**
     * Delete a user's account (admin-only).
     */
    public function deleteUserAccount(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = User::findOrFail($id);

        // Prevent deleting the current admin account
        if ($user->id === $request->user()->id) {
            return Redirect::route('admin.dashboard')->withErrors('You cannot delete your own account.');
        }

        $user->delete();

        return Redirect::route('admin.users.viewAll');
    }
}
