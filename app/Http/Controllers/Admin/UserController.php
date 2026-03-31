<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $roleName = $request->get('role');
        
        $users = User::with('role')
            ->where('status', 'active')
            ->orderBy('id')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleName, function ($query) use ($roleName) {
                $query->whereHas('role', function ($q) use ($roleName) {
                    $q->where('role_name', $roleName);
                });
            })
            ->paginate(10);
        
        $counts = [
            'total' => User::where('status', 'active')->count(),
            'admin' => User::where('status', 'active')->whereHas('role', fn($q) => $q->where('role_name', 'admin'))->count(),
            'teacher' => User::where('status', 'active')->whereHas('role', fn($q) => $q->where('role_name', 'teacher'))->count(),
            'student' => User::where('status', 'active')->whereHas('role', fn($q) => $q->where('role_name', 'student'))->count(),
        ];

        $dashboardTitle = 'User Management';
        $userRole = 'admin';

        return view('admin.users.index', compact('users', 'search', 'roleName', 'counts', 'dashboardTitle', 'userRole'));
    }

    public function create(Request $request)
    {
        $roleName = $request->get('role');
        $roles = Role::all();
        return view('admin.users.create', compact('roles', 'roleName'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $userData = [
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'birthday' => $request->birthday,
                'sex' => $request->sex,
                'status' => 'active'
            ];

            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $userData['profile_photo'] = $path;
            }

            User::create($userData);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'A system error occurred.');
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->username = $request->username;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->birthday = $request->birthday;
            $user->sex = $request->sex;
            $user->role_id = $request->role_id;
            $user->auth_method = $request->auth_method ?? 'manual';
            $user->is_suspended = $request->boolean('is_suspended');
            $user->force_password_change = $request->boolean('force_password_change');

            if ($request->filled('password')) {
                $user->password_hash = Hash::make($request->password);
            }

            if ($request->hasFile('profile_photo')) {
                // Delete old photo if it exists
                if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
                }
                $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
            }

            $user->save();
            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'A system error occurred.');
        }
    }

    public function destroy(User $user)
    {
        // OWASP: Protection against self-deletion
        if ((int)$user->id === (int)auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself.');
        }

        // We set status to inactive instead of permanent deletion to preserve records
        $user->update(['status' => 'inactive']);
        
        return redirect()->route('admin.users.index')->with('success', 'User account has been deactivated.');
    }
}
