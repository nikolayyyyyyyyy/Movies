<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateUserProfilePictureRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, ?User $user = null): Response
    {
        $targetUser = $user ?? $request->user();
        $this->authorize('update', $targetUser);

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'targetUser' => $targetUser,
            'isOwnProfile' => $request->user()->is($targetUser),
            'profileUpdateRoute' => $user
                ? route('profile.update-user', ['user' => $targetUser->name])
                : route('profile.update'),
            'profilePictureUpdateRoute' => $user
                ? route('profile.update-profile-picture-user', ['user' => $targetUser->name])
                : route('profile.update-profile-picture'),
            'profilePictureDestroyRoute' => $user
                ? route('users.destroy-profile-picture-user', ['user' => $targetUser->name])
                : route('users.destroy-profile-picture'),
            'roles' => Role::all(),
            'canManageRole' => $request->user()->can('create', User::class),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ?User $user = null): RedirectResponse
    {
        $targetUser = $user ?? $request->user();
        $this->authorize('update', $targetUser);
        $targetUser->fill($request->validated());

        if ($targetUser->isDirty('email')) {
            $targetUser->email_verified_at = null;
        }

        $targetUser->save();

        return $user
            ? Redirect::route('profile.edit-user', ['user' => $targetUser->name])
            : Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->authorize('delete', $request->user());

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's profile picture.
     */
    public function updateProfilePicture(UpdateUserProfilePictureRequest $request, ?User $user = null): RedirectResponse
    {
        $targetUser = $user ?? $request->user();
        $this->authorize('update', $targetUser);

        if ($targetUser->profile_picture) {
            $path = $targetUser->getRawOriginal('profile_picture');
            Storage::disk('public')->delete($path);
        }

        $targetUser->update([
            'profile_picture' => $request->file('profile_picture')->store('profile_pictures', 'public'),
        ]);
        $targetUser->refresh();

        return $user
            ? redirect()->route('profile.edit-user', ['user' => $targetUser->name])
            : redirect()->route('profile.edit');
    }

    public function destroyProfilePicture(Request $request, ?User $user = null)
    {
        $targetUser = $user ?? $request->user();
        $this->authorize('update', $targetUser);

        $targetUser->profile_picture = null;
        $targetUser->save();

        return Redirect::back();
    }
}
