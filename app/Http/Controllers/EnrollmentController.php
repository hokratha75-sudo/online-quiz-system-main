<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Subject;
use App\Models\User;
use App\Models\EnrollmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;

class EnrollmentController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // INDEX  –  admin.enrollments.index
    // ─────────────────────────────────────────────────────────────
    public function index()
    {
        // Eager-load everything the view touches
        $departments = Department::with([
            'users',           // all users in the department
            'subjects',        // department subjects
            'majors.classes',  // majors → classes (used for classCount)
        ])->get();

        $stats = [
            'total_faculty'   => User::where('role_id', 2)->count(),
            'total_students'  => User::where('role_id', 3)->count(),
            'total_subjects'  => Subject::count(),
        ];

        return view('admin.enrollments.index', compact('departments', 'stats'));
    }

    // ─────────────────────────────────────────────────────────────
    // MANAGE  –  admin.enrollments.manage
    // ─────────────────────────────────────────────────────────────
    public function manage(Department $department)
    {
        // All classes in this department (via majors)
        $classes = \App\Models\SchoolClass::whereHas('major', fn($q) =>
            $q->where('department_id', $department->id)
        )->with(['students', 'teachers', 'major'])->get();

        // All users enrolled in ANY class in this department
        $enrolledUsers = User::whereHas('classes', fn($q) =>
            $q->whereHas('major', fn($q2) =>
                $q2->where('department_id', $department->id)
            )
        )->with('classes')->get();

        $subjects         = Subject::where('department_id', $department->id)->get();
        $allTeachers      = User::where('role_id', 2)->orderBy('username')->get();
        $allStudents      = User::where('role_id', 3)->orderBy('username')->get();
        $assignedSubjects = $department->subjects()->pluck('subjects.id')->toArray();

        return view('admin.enrollments.manage', compact(
            'department', 'classes', 'enrolledUsers',
            'subjects', 'allTeachers', 'allStudents', 'assignedSubjects'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE  –  admin.enrollments.update  (PUT)
    // ─────────────────────────────────────────────────────────────
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'class_assignments'            => 'nullable|array',
            'class_assignments.*.teachers' => 'nullable|array',
            'class_assignments.*.students' => 'nullable|array',
            'subjects'                     => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $department) {
            $adminId = Auth::id();

            // ── Class assignments ────────────────────────────────
            foreach ($request->input('class_assignments', []) as $classId => $groups) {
                $class = \App\Models\SchoolClass::find($classId);
                if (! $class) {
                    continue;
                }

                $newTeacherIds = $groups['teachers'] ?? [];
                $newStudentIds = $groups['students'] ?? [];

                $oldTeacherIds = $class->teachers()->pluck('users.id')->toArray();
                $oldStudentIds = $class->students()->pluck('users.id')->toArray();

                // Sync teachers
                $class->teachers()->sync($newTeacherIds);
                foreach (array_diff($newTeacherIds, $oldTeacherIds) as $uid) {
                    EnrollmentHistory::create([
                        'department_id' => $department->id,
                        'class_id'      => $class->id,
                        'user_id'       => $uid,
                        'admin_id'      => $adminId,
                        'action'        => 'enrolled',
                        'reason'        => 'Teacher assigned via manage page',
                    ]);
                }
                foreach (array_diff($oldTeacherIds, $newTeacherIds) as $uid) {
                    EnrollmentHistory::create([
                        'department_id' => $department->id,
                        'class_id'      => $class->id,
                        'user_id'       => $uid,
                        'admin_id'      => $adminId,
                        'action'        => 'unenrolled',
                        'reason'        => 'Teacher removed via manage page',
                    ]);
                }

                // Sync students
                $class->students()->sync($newStudentIds);
                foreach (array_diff($newStudentIds, $oldStudentIds) as $uid) {
                    EnrollmentHistory::create([
                        'department_id' => $department->id,
                        'class_id'      => $class->id,
                        'user_id'       => $uid,
                        'admin_id'      => $adminId,
                        'action'        => 'enrolled',
                        'reason'        => 'Student enrolled via manage page',
                    ]);
                }
                foreach (array_diff($oldStudentIds, $newStudentIds) as $uid) {
                    EnrollmentHistory::create([
                        'department_id' => $department->id,
                        'class_id'      => $class->id,
                        'user_id'       => $uid,
                        'admin_id'      => $adminId,
                        'action'        => 'unenrolled',
                        'reason'        => 'Student removed via manage page',
                    ]);
                }
            }

            // ── Subject assignments ──────────────────────────────
            $oldSubjectIds = $department->subjects()->pluck('subjects.id')->toArray();
            $newSubjectIds = $request->input('subjects', []);

            $department->subjects()->sync($newSubjectIds);

            foreach (array_diff($newSubjectIds, $oldSubjectIds) as $sid) {
                EnrollmentHistory::create([
                    'department_id' => $department->id,
                    'subject_id'    => $sid,
                    'admin_id'      => $adminId,
                    'action'        => 'subject_assigned',
                    'reason'        => 'Subject assigned via manage page',
                ]);
            }
            foreach (array_diff($oldSubjectIds, $newSubjectIds) as $sid) {
                EnrollmentHistory::create([
                    'department_id' => $department->id,
                    'subject_id'    => $sid,
                    'admin_id'      => $adminId,
                    'action'        => 'subject_removed',
                    'reason'        => 'Subject removed via manage page',
                ]);
            }
        });

        return redirect()
            ->route('admin.enrollments.manage', $department->id)
            ->with('success', 'Enrollment updated successfully.');
    }

    // ─────────────────────────────────────────────────────────────
    // STATISTICS  –  admin.enrollments.statistics
    // ─────────────────────────────────────────────────────────────
    public function statistics(Department $department)
    {
        $classes = \App\Models\SchoolClass::whereHas('major', fn($q) =>
            $q->where('department_id', $department->id)
        )->with(['students', 'teachers'])->get();

        $studentCounts = $classes->map(fn($c) => $c->students->count());
        $avgStudents   = $classes->count()
            ? round($studentCounts->average(), 1)
            : 0;

        $totalStudents  = \App\Models\SchoolClass::whereHas('major', fn($q) =>
            $q->where('department_id', $department->id)
        )->withCount('students')->get()->sum('students_count');

        $totalTeachers  = \App\Models\SchoolClass::whereHas('major', fn($q) =>
            $q->where('department_id', $department->id)
        )->withCount('teachers')->get()->sum('teachers_count');

        $stats = [
            'classes_count'              => $classes->count(),
            'total_students'             => $totalStudents,
            'total_teachers'             => $totalTeachers,
            'total_subjects'             => $department->subjects()->count(),
            'average_students_per_class' => $avgStudents,
        ];

        $history = EnrollmentHistory::with(['user', 'subject', 'class', 'admin'])
            ->where('department_id', $department->id)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.enrollments.statistics', compact('department', 'stats', 'history'));
    }

    // ─────────────────────────────────────────────────────────────
    // HISTORY  –  admin.enrollments.history
    // ─────────────────────────────────────────────────────────────
    public function history(Department $department)
    {
        $history = EnrollmentHistory::with(['user', 'subject', 'class', 'admin'])
            ->where('department_id', $department->id)
            ->latest()
            ->paginate(50);

        return view('admin.enrollments.history', compact('department', 'history'));
    }

    // ─────────────────────────────────────────────────────────────
    // EXPORT CSV  –  admin.enrollments.export.history
    // ─────────────────────────────────────────────────────────────
    public function exportHistory(Department $department)
    {
        $records = EnrollmentHistory::with(['user', 'subject', 'class', 'admin'])
            ->where('department_id', $department->id)
            ->latest()
            ->get();

        $filename = 'enrollment_history_' . $department->id . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date & Time', 'Action', 'User/Subject', 'Class', 'Details', 'Admin']);
            foreach ($records as $r) {
                fputcsv($handle, [
                    $r->created_at->format('Y-m-d H:i:s'),
                    ucfirst(str_replace('_', ' ', $r->action)),
                    $r->user?->username ?? $r->subject?->subject_name ?? '—',
                    $r->class?->class_name ?? '—',
                    $r->reason ?? '—',
                    $r->admin?->username ?? 'System',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─────────────────────────────────────────────────────────────
    // IMPORT  –  admin.enrollments.import  (GET + POST)
    // ─────────────────────────────────────────────────────────────
    public function importForm(Department $department)
    {
        return view('admin.enrollments.import', compact('department'));
    }

    public function import(Request $request, Department $department)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path    = $request->file('file')->getRealPath();
        $lines   = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $headers = null;

        $results = ['success' => 0, 'errors' => 0, 'skipped' => 0, 'total' => 0, 'errors_list' => []];

        foreach ($lines as $i => $line) {
            $row = str_getcsv($line);
            if ($i === 0) {
                $headers = array_map('trim', $row);
                continue;
            }

            $results['total']++;
            if (count($row) < 4) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: insufficient columns.";
                continue;
            }

            $data = array_combine(array_slice($headers, 0, count($row)), $row);

            $className  = trim($data['Class Name'] ?? '');
            $userType   = strtolower(trim($data['User Type']  ?? ''));
            $username   = trim($data['Username']   ?? '');
            $subjectName = trim($data['Subject']   ?? '');

            // Find user
            $roleId = match($userType) {
                'student' => 3,
                'teacher' => 2,
                default   => null,
            };

            if (! $roleId) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: Unknown user type '{$userType}'.";
                continue;
            }

            $user = User::where('username', $username)->where('role_id', $roleId)->first();
            if (! $user) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: User '{$username}' not found.";
                continue;
            }

            // Find class
            $class = \App\Models\SchoolClass::whereHas('major', fn($q) =>
                $q->where('department_id', $department->id)
            )->where('class_name', $className)->first();

            if (! $class) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: Class '{$className}' not found in this department.";
                continue;
            }

            // Attach user to class (skip if already enrolled)
            $pivotTable = $roleId === 2 ? 'class_teacher' : 'class_student';
            $alreadyIn  = DB::table($pivotTable)
                ->where('class_id', $user->id)
                ->where('user_id',  $user->id)
                ->exists();

            if ($alreadyIn) {
                $results['skipped']++;
                continue;
            }

            if ($roleId === 2) {
                $class->teachers()->syncWithoutDetaching([$user->id]);
            } else {
                $class->students()->syncWithoutDetaching([$user->id]);
            }

            EnrollmentHistory::create([
                'department_id' => $department->id,
                'class_id'      => $class->id,
                'user_id'       => $user->id,
                'admin_id'      => Auth::id(),
                'action'        => 'enrolled',
                'reason'        => "Imported via CSV",
            ]);

            $results['success']++;
        }

        return redirect()
            ->route('admin.enrollments.import', $department->id)
            ->with('import_results', $results);
    }
}