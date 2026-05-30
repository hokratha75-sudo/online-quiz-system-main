<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Department;
use App\Models\Major;

echo "=== Testing Enrollment System ===\n\n";

// Get or create test data
$department = Department::firstOrCreate(['department_name' => 'Computer Science'], ['code' => 'CS']);
echo "Department: {$department->department_name} (ID: {$department->id})\n";

// Get or create major
$major = Major::firstOrCreate(['name' => 'Computer Science - Main'], ['code' => 'CSM', 'department_id' => $department->id]);
echo "Major: {$major->name} (ID: {$major->id})\n";

// Get or create class
$class = ClassModel::firstOrCreate(
    ['class_name' => 'CS - Batch 2026'],
    ['major_id' => $major->id, 'academic_year' => '2026']
);
echo "Class: {$class->class_name} (ID: {$class->id})\n\n";

// Get test users
$teachers = User::where('role_id', 2)->limit(2)->get();
$students = User::where('role_id', 3)->limit(3)->get();

echo "Found Teachers: {$teachers->count()}\n";
foreach ($teachers as $t) {
    echo "  - {$t->username} (ID: {$t->id})\n";
}

echo "Found Students: {$students->count()}\n";
foreach ($students as $s) {
    echo "  - {$s->username} (ID: {$s->id})\n";
}

// Get subjects
$subjects = Subject::limit(2)->get();
echo "\nFound Subjects: {$subjects->count()}\n";
foreach ($subjects as $s) {
    echo "  - {$s->subject_name} (ID: {$s->id})\n";
}

echo "\n=== Testing Enrollment ===\n\n";

// Enroll teachers
if ($teachers->isNotEmpty()) {
    $teacherSyncData = $teachers->mapWithKeys(function($t) {
        return [$t->id => ['role' => 'teacher']];
    })->toArray();
    
    $class->users()->sync($teacherSyncData);
    echo "✓ Enrolled {$teachers->count()} teachers in class\n";
}

// Enroll students
if ($students->isNotEmpty()) {
    $studentSyncData = $students->mapWithKeys(function($s) {
        return [$s->id => ['role' => 'student']];
    })->toArray();
    
    // First remove existing student enrollments
    $class->students()->detach();
    
    // Then add new ones
    $class->users()->attach($studentSyncData);
    echo "✓ Enrolled {$students->count()} students in class\n";
}

// Assign subjects
if ($subjects->isNotEmpty()) {
    $class->subjects()->sync($subjects->pluck('id')->toArray());
    echo "✓ Assigned {$subjects->count()} subjects to class\n";
}

echo "\n=== Verification ===\n\n";

// Verify enrollments
$classTeachers = $class->users()->wherePivot('role', 'teacher')->get();
$classStudents = $class->users()->wherePivot('role', 'student')->get();
$classSubjects = $class->subjects()->get();

echo "Teachers in class: {$classTeachers->count()}\n";
foreach ($classTeachers as $t) {
    echo "  ✓ {$t->username} (Role: {$t->pivot->role})\n";
}

echo "\nStudents in class: {$classStudents->count()}\n";
foreach ($classStudents as $s) {
    echo "  ✓ {$s->username} (Role: {$s->pivot->role})\n";
}

echo "\nSubjects in class: {$classSubjects->count()}\n";
foreach ($classSubjects as $s) {
    echo "  ✓ {$s->subject_name}\n";
}

// Test student course access (from SubjectController myCourses logic)
echo "\n=== Testing Student Course Access ===\n\n";
if ($students->isNotEmpty()) {
    $testStudent = $students->first();
    
    // Check enrolled courses
    $enrolledSubjectIds = ClassModel::whereHas('students', function($q) use ($testStudent) {
        $q->where('user_id', $testStudent->id);
    })->with('subjects')->get()->flatMap(function($class) {
        return $class->subjects->pluck('id');
    })->unique()->toArray();
    
    $enrolledSubjects = Subject::whereIn('id', $enrolledSubjectIds)->get();
    
    echo "Courses for {$testStudent->username} (ID: {$testStudent->id}):\n";
    if ($enrolledSubjects->isNotEmpty()) {
        foreach ($enrolledSubjects as $s) {
            echo "  ✓ {$s->subject_name} (ID: {$s->id})\n";
        }
    } else {
        echo "  ✗ No courses found\n";
    }
}

echo "\n=== Enrollment Test Complete ===\n";
