<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $dashboardTitle = 'Edit Profile';
        $loginHistories = $user->loginHistories()->latest('login_at')->take(10)->get();
        return view('profile.edit', compact('user', 'dashboardTitle', 'loginHistories'));
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            
            // Basic Info
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            
            // Additional Info - Convert empty strings to NULL for database compatibility
            $user->phone = !empty($request->phone) ? $request->phone : null;
            $user->address = !empty($request->address) ? $request->address : null;
            $user->birthday = !empty($request->birthday) ? $request->birthday : null;
            $user->sex = !empty($request->sex) ? $request->sex : null;

            // Password Security
            if ($request->filled('password')) {
                $user->password_hash = Hash::make($request->password);
            }

            // Photo Management
            if ($request->hasFile('profile_photo')) {
                // Delete old photo
                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
            }

            $user->save();

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
}
