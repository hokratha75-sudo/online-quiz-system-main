<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$username = 'Ratha';
$email = 'ratha@gmail.com';
$password = 'ratha123';

$user = User::where('username', $username)->orWhere('email', $email)->first();

if ($user) {
    // Update existing user
    $user->username = $username;
    $user->email = $email;
    $user->password_hash = Hash::make($password);
    $user->role_id = 1; // Admin
    $user->status = 'active';
    $user->save();
    echo "Success: Account '{$username}' has been updated. Password is: {$password}\n";
} else {
    // Create new user
    User::create([
        'username' => $username,
        'email' => $email,
        'password_hash' => Hash::make($password),
        'role_id' => 1, // Admin
        'status' => 'active'
    ]);
    echo "Success: Account '{$username}' has been created as Admin. Password is: {$password}\n";
}
