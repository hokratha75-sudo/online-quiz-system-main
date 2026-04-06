<?php
use App\Models\User;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Major;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

$user = User::where('email', 'admin@gmail.com')->first();
if (!$user) {
    echo "Error: User admin@gmail.com not found.\n";
    exit(1);
}

// 1. Ensure a Department exists
$dept = Department::first() ?: Department::create(['department_name' => 'General Science', 'code' => 'GS']);

// 2. Ensure a Major exists
$major = Major::first() ?: Major::create(['name' => 'Computer Science', 'department_id' => $dept->id]);

// 3. Create a Subject
$subject = Subject::create([
    'subject_name' => 'Advanced Programming with AI',
    'code' => 'AI101',
    'department_id' => $dept->id,
    'major_id' => $major->id,
    'credits' => 4,
    'created_by' => $user->id
]);

// 4. Create a Class
$class = ClassModel::create([
    'name' => 'Elite Tech Batch 01',
    'code' => 'ETB01',
    'major_id' => $major->id,
    'academic_year' => '2026'
]);

// 5. Link Subject to Class
$class->subjects()->attach($subject->id);

// 6. Enroll Admin to Class
$class->users()->attach($user->id, ['role' => 'admin']);

echo "Success! Subject '{$subject->subject_name}' assigned to user '{$user->name}' (ID: {$user->id}) via Class '{$class->name}'.\n";
