<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        if ($request->user()->hasRole('student')) {
            return view('profile.student-edit', [
                'user' => $request->user()->load('studentProfile.classroom'),
            ]);
        }

        if ($request->user()->hasRole('parent')) {
            return view('profile.parent-edit', [
                'user' => $request->user()->load('parentProfile'),
            ]);
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $profileData = [
            'phone' => $validated['phone'] ?? null,
        ];

        unset($validated['phone']);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        if ($request->user()->hasRole('parent')) {
            $request->user()->parentProfile()->updateOrCreate(
                ['user_id' => $request->user()->id],
                $profileData
            );
        }

        if ($request->user()->hasRole('student')) {
            $request->user()->studentProfile()->updateOrCreate(
                ['user_id' => $request->user()->id],
                $profileData
            );
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
