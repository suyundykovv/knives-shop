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
use Illuminate\Routing\Controller as BaseController;


class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function dashboard(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            return redirect('/');
        }

        return Inertia::render('Admin/Dashboard', [
            'users' => User::all(),
            'status' => session('status'),
        ]);
    }

    public function editProfile(Request $request): Response
    {
        return Inertia::render('Admin/Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }


    public function updateProfile(AdminUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('admin.profile.edit');
    }

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


    public function viewAllUsers(Request $request): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::all(),
        ]);
    }


    public function editUserProfile($id): Response
    {
        $user = User::findOrFail($id);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
        ]);
    }


    public function updateUserProfile(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'is_admin' => 'boolean',
        ]);

        $user->fill($validated);
        $user->save();

        return Redirect::route('admin.dashboard')->with('status', 'User updated successfully!');
    }

    public function deleteUserAccount($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return Redirect::route('admin.dashboard')->withErrors('You cannot delete your own account.');
        }

        $user->delete();

        return Redirect::route('admin.dashboard')->with('status', 'User deleted successfully!');
    }
}
