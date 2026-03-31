<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Subject;

function checkUserCourses($username) {
    $user = User::where('username', $username)->first();
    if (!$user) {
        echo "User $username not found.\n";
        return;
    }

    $subjects = Subject::whereHas('classes.users', function($q) use ($user) {
        $q->where('users.id', $user->id);
    })->get();

    echo "Courses for $username (ID: {$user->id}, Role: {$user->role_id}):\n";
    foreach ($subjects as $s) {
        echo "- [{$s->code}] {$s->subject_name}\n";
    }
    if ($subjects->isEmpty()) {
        echo "- No courses found.\n";
    }
    echo "\n";
}

checkUserCourses('teacher2');
checkUserCourses('student1');
checkUserCourses('admin');
