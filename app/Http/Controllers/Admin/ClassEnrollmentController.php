<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassEnrollmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['users', 'subjects'])->orderBy('department_name')->get();
        
        $stats = [
            'total_faculty' => User::where('role_id', 2)->count(),
            'total_students' => User::where('role_id', 3)->count(),
            'total_subjects' => Subject::count(),
        ];
        
        $dashboardTitle = 'Academic Enrollments';
        $userRole = 'admin';

        return view('admin.enrollments.index', compact('departments', 'dashboardTitle', 'userRole', 'stats'));
    }

    public function manage(Department $department)
    {
        $department->load(['users.role', 'subjects']);
        
        $enrolledUsers = $department->users()->orderBy('username')->get();
        
        $teachers = User::where('role_id', 2)->orderBy('username')->get();
        $students = User::where('role_id', 3)->orderBy('username')->get();
        $subjects = Subject::orderBy('subject_name')->get();

        $enrolledStudents = $department->users()->where('role_id', 3)->pluck('id')->toArray();
        $assignedTeachers = $department->users()->where('role_id', 2)->pluck('id')->toArray();
        $assignedSubjects = $department->subjects()->pluck('id')->toArray();

        // Count of enrolled participants
        $participantCount = $enrolledUsers->count();

        $dashboardTitle = 'Participants: ' . $department->department_name;
        $userRole = 'admin';

        return view('admin.enrollments.manage', compact(
            'department', 'enrolledUsers', 'teachers', 'students', 'subjects',
            'enrolledStudents', 'assignedTeachers', 'assignedSubjects', 
            'dashboardTitle', 'userRole', 'participantCount'
        ));
    }

    public function update(Request $request, Department $department)
    {
        \Log::info('Updating Enrollment', ['request' => $request->all(), 'department_id' => $department->id]);
        $request->validate([
            'students' => 'nullable|array',
            'students.*' => 'exists:users,id',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:users,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        User::where('department_id', $department->id)
            ->whereIn('role_id', [2, 3])
            ->update(['department_id' => null]);
            
        Subject::where('department_id', $department->id)
            ->update(['department_id' => null]);

        if ($request->filled('students')) {
            User::whereIn('id', $request->students)->update(['department_id' => $department->id]);
        }
        if ($request->filled('teachers')) {
            User::whereIn('id', $request->teachers)->update(['department_id' => $department->id]);
        }
        if ($request->filled('subjects')) {
            Subject::whereIn('id', $request->subjects)->update(['department_id' => $department->id]);
        }

        return redirect()->route('admin.enrollments.index')->with('success', 'Department assignments updated successfully.');
    }
}
