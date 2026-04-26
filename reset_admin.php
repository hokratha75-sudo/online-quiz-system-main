<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$username = 'admin';
$newPassword = 'admin123';

$user = User::where('username', $username)->first();

if ($user) {
    $user->password_hash = Hash::make($newPassword);
    $user->save();
    echo "Success: Password for '{$username}' has been reset to '{$newPassword}'.\n";
} else {
    echo "Error: User '{$username}' not found. Creating a new admin account...\n";
    User::create([
        'username' => $username,
        'email' => 'admin@gmail.com',
        'password_hash' => Hash::make($newPassword),
        'role_id' => 1,
        'status' => 'active'
    ]);
    echo "Success: New admin account created with password '{$newPassword}'.\n";
}

echo "\nListing all users in the system:\n";
$users = User::all(['username', 'email']);
foreach ($users as $u) {
    echo "- Username: {$u->username}, Email: {$u->email}\n";
}
