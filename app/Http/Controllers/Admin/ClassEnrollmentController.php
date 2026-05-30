<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Department;
use App\Models\Subject;
use App\Models\User;
use App\Models\EnrollmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassEnrollmentController extends Controller
{
    private function departmentClasses(Department $department)
    {
        return ClassModel::whereHas('major', fn($q) =>
            $q->where('department_id', $department->id)
        )->with(['major', 'students', 'teachers']);
    }

    // JSON-safe logger — old_value/new_value must be null or json string
    private function log(array $data)
    {
        EnrollmentHistory::create(array_merge([
            'admin_id'   => Auth::id(),
            'reason'     => null,
            'old_value'  => null,
            'new_value'  => null,
        ], $data));
    }

    // ── INDEX ────────────────────────────────────────────────────────────────
    public function index()
    {
        $departments = Department::with(['majors.classes', 'subjects'])->get();

        $departments->each(function ($dept) {
            $classIds = $dept->majors->flatMap(fn($m) => $m->classes->pluck('id'));
            $dept->faculty_count = DB::table('class_user')
                ->whereIn('class_model_id', $classIds)->where('role', 'teacher')
                ->distinct('user_id')->count('user_id');
            $dept->student_count = DB::table('class_user')
                ->whereIn('class_model_id', $classIds)->where('role', 'student')
                ->distinct('user_id')->count('user_id');
        });

        $stats = [
            'total_faculty'  => DB::table('class_user')->where('role', 'teacher')->distinct('user_id')->count('user_id'),
            'total_students' => DB::table('class_user')->where('role', 'student')->distinct('user_id')->count('user_id'),
            'total_subjects' => Subject::count(),
        ];

        return view('admin.enrollments.index', compact('departments', 'stats'));
    }

    // ── MANAGE ───────────────────────────────────────────────────────────────
    public function manage(Department $department)
    {
        $classes  = $this->departmentClasses($department)->get();
        $classIds = $classes->pluck('id');

        $enrolledUserIds = DB::table('class_user')
            ->whereIn('class_model_id', $classIds)
            ->pluck('user_id')->unique();

        $enrolledUsers = User::whereIn('id', $enrolledUserIds)
            ->with(['classes'])->get();

        $subjects         = Subject::where('department_id', $department->id)->get();
        $allTeachers      = User::where('role_id', 2)->orderBy('username')->get();
        $allStudents      = User::where('role_id', 3)->orderBy('username')->get();
        $assignedSubjects = DB::table('class_subject')
            ->whereIn('class_model_id', $classIds)
            ->pluck('subject_id')->unique()->toArray();

        return view('admin.enrollments.manage', compact(
            'department', 'classes', 'enrolledUsers',
            'subjects', 'allTeachers', 'allStudents', 'assignedSubjects'
        ));
    }

    // ── UPDATE ───────────────────────────────────────────────────────────────
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'class_assignments'            => 'nullable|array',
            'class_assignments.*.teachers' => 'nullable|array',
            'class_assignments.*.students' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $department) {
            foreach ($request->input('class_assignments', []) as $classId => $groups) {
                $class = ClassModel::find($classId);
                if (! $class) continue;

                $newTeacherIds = array_map('intval', $groups['teachers'] ?? []);
                $newStudentIds = array_map('intval', $groups['students'] ?? []);

                $existing      = DB::table('class_user')->where('class_model_id', $classId)->get();
                $oldTeacherIds = $existing->where('role', 'teacher')->pluck('user_id')->map(fn($v) => (int)$v)->toArray();
                $oldStudentIds = $existing->where('role', 'student')->pluck('user_id')->map(fn($v) => (int)$v)->toArray();

                // ── Teachers ──────────────────────────────────────────────
                foreach (array_diff($newTeacherIds, $oldTeacherIds) as $uid) {
                    DB::table('class_user')->insert([
                        'class_model_id' => $classId, 'user_id' => $uid,
                        'role' => 'teacher', 'created_at' => now(), 'updated_at' => now(),
                    ]);
                    $this->log([
                        'department_id'  => $department->id,
                        'class_model_id' => $classId,
                        'user_id'        => $uid,
                        'action'         => 'enrolled',
                        'action_type'    => 'teacher',
                        'new_value'      => json_encode('teacher'),  // ← valid JSON
                        'old_value'      => null,
                    ]);
                }
                foreach (array_diff($oldTeacherIds, $newTeacherIds) as $uid) {
                    DB::table('class_user')
                        ->where('class_model_id', $classId)->where('user_id', $uid)->where('role', 'teacher')
                        ->delete();
                    $this->log([
                        'department_id'  => $department->id,
                        'class_model_id' => $classId,
                        'user_id'        => $uid,
                        'action'         => 'unenrolled',
                        'action_type'    => 'teacher',
                        'old_value'      => json_encode('teacher'),  // ← valid JSON
                        'new_value'      => null,
                    ]);
                }

                // ── Students ──────────────────────────────────────────────
                foreach (array_diff($newStudentIds, $oldStudentIds) as $uid) {
                    DB::table('class_user')->insert([
                        'class_model_id' => $classId, 'user_id' => $uid,
                        'role' => 'student', 'created_at' => now(), 'updated_at' => now(),
                    ]);
                    $this->log([
                        'department_id'  => $department->id,
                        'class_model_id' => $classId,
                        'user_id'        => $uid,
                        'action'         => 'enrolled',
                        'action_type'    => 'student',
                        'new_value'      => json_encode('student'),  // ← valid JSON
                        'old_value'      => null,
                    ]);
                }
                foreach (array_diff($oldStudentIds, $newStudentIds) as $uid) {
                    DB::table('class_user')
                        ->where('class_model_id', $classId)->where('user_id', $uid)->where('role', 'student')
                        ->delete();
                    $this->log([
                        'department_id'  => $department->id,
                        'class_model_id' => $classId,
                        'user_id'        => $uid,
                        'action'         => 'unenrolled',
                        'action_type'    => 'student',
                        'old_value'      => json_encode('student'),  // ← valid JSON
                        'new_value'      => null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.enrollments.manage', $department->id)
            ->with('success', 'Enrollment updated successfully.');
    }

    // ── STATISTICS ───────────────────────────────────────────────────────────
    public function statistics(Department $department)
    {
        $classes  = $this->departmentClasses($department)->get();
        $classIds = $classes->pluck('id');

        $stats = [
            'classes_count'              => $classes->count(),
            'total_students'             => DB::table('class_user')->whereIn('class_model_id', $classIds)->where('role', 'student')->distinct('user_id')->count('user_id'),
            'total_teachers'             => DB::table('class_user')->whereIn('class_model_id', $classIds)->where('role', 'teacher')->distinct('user_id')->count('user_id'),
            'total_subjects'             => DB::table('class_subject')->whereIn('class_model_id', $classIds)->distinct('subject_id')->count('subject_id'),
            'average_students_per_class' => $classes->count() ? round($classes->average(fn($c) => $c->students->count()), 1) : 0,
        ];

        $history = EnrollmentHistory::with(['user', 'subject', 'classModel', 'admin'])
            ->where('department_id', $department->id)->latest()->take(10)->get();

        return view('admin.enrollments.statistics', compact('department', 'stats', 'history'));
    }

    // ── HISTORY ──────────────────────────────────────────────────────────────
    public function history(Department $department)
    {
        $history = EnrollmentHistory::with(['user', 'subject', 'classModel', 'admin'])
            ->where('department_id', $department->id)->latest()->paginate(50);

        return view('admin.enrollments.history', compact('department', 'history'));
    }

    // ── EXPORT HISTORY CSV ───────────────────────────────────────────────────
    public function exportHistory(Department $department)
    {
        $records  = EnrollmentHistory::with(['user', 'subject', 'classModel', 'admin'])
            ->where('department_id', $department->id)->latest()->get();
        $filename = 'enrollment_history_' . $department->id . '_' . now()->format('Ymd_His') . '.csv';

        return response()->stream(function () use ($records) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Date & Time', 'Action', 'Type', 'User/Subject', 'Class', 'Details', 'Admin']);
            foreach ($records as $r) {
                fputcsv($h, [
                    $r->created_at->format('Y-m-d H:i:s'),
                    ucfirst($r->action),
                    $r->action_type ?? '—',
                    $r->user?->username ?? $r->subject?->subject_name ?? '—',
                    $r->classModel?->name ?? '—',
                    $r->reason ?? '—',
                    $r->admin?->username ?? 'System',
                ]);
            }
            fclose($h);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // ── EXPORT enrollment list ────────────────────────────────────────────────
    public function export(Department $department)
    {
        $classes  = $this->departmentClasses($department)->get();
        $filename = 'enrollment_' . $department->id . '_' . now()->format('Ymd_His') . '.csv';

        return response()->stream(function () use ($classes, $department) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Department', 'Class', 'Role', 'Username', 'Email']);
            foreach ($classes as $class) {
                foreach ($class->teachers as $u) {
                    fputcsv($h, [$department->department_name, $class->name, 'Teacher', $u->username, $u->email]);
                }
                foreach ($class->students as $u) {
                    fputcsv($h, [$department->department_name, $class->name, 'Student', $u->username, $u->email]);
                }
            }
            fclose($h);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    // ── IMPORT FORM ──────────────────────────────────────────────────────────
    public function showImport(Department $department)
    {
        return view('admin.enrollments.import', compact('department'));
    }

    // ── IMPORT ───────────────────────────────────────────────────────────────
    public function import(Request $request, Department $department)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:10240']);

        $lines   = file($request->file('file')->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $headers = null;
        $results = ['success' => 0, 'errors' => 0, 'skipped' => 0, 'total' => 0, 'errors_list' => []];

        foreach ($lines as $i => $line) {
            $row = str_getcsv($line);
            if ($i === 0) { $headers = array_map('trim', $row); continue; }

            $results['total']++;
            if (count($row) < 3) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: insufficient columns.";
                continue;
            }

            $data      = array_combine(array_slice($headers, 0, count($row)), $row);
            $className = trim($data['Class Name'] ?? '');
            $userType  = strtolower(trim($data['User Type'] ?? ''));
            $username  = trim($data['Username'] ?? '');
            $role      = match($userType) { 'teacher' => 'teacher', 'student' => 'student', default => null };

            if (! $role) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: Unknown user type '{$userType}'.";
                continue;
            }

            $user = User::where('username', $username)->first();
            if (! $user) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: User '{$username}' not found.";
                continue;
            }

            $class = ClassModel::whereHas('major', fn($q) =>
                $q->where('department_id', $department->id)
            )->where('name', $className)->first();

            if (! $class) {
                $results['errors']++;
                $results['errors_list'][] = "Row {$i}: Class '{$className}' not found.";
                continue;
            }

            $exists = DB::table('class_user')
                ->where('class_model_id', $class->id)
                ->where('user_id', $user->id)
                ->where('role', $role)->exists();

            if ($exists) { $results['skipped']++; continue; }

            DB::table('class_user')->insert([
                'class_model_id' => $class->id, 'user_id' => $user->id,
                'role' => $role, 'created_at' => now(), 'updated_at' => now(),
            ]);

            $this->log([
                'department_id'  => $department->id,
                'class_model_id' => $class->id,
                'user_id'        => $user->id,
                'action'         => 'enrolled',
                'action_type'    => $role,
                'new_value'      => json_encode($role),  // ← valid JSON
                'old_value'      => null,
                'reason'         => 'Imported via CSV',
            ]);

            $results['success']++;
        }

        return redirect()
            ->route('admin.enrollments.showImport', $department->id)
            ->with('import_results', $results);
    }

    // ── AJAX ─────────────────────────────────────────────────────────────────
    public function getClasses(Department $department)
    {
        return response()->json(
            $this->departmentClasses($department)->get()->map(fn($c) => [
                'id' => $c->id, 'name' => $c->name,
                'students_count' => $c->students->count(),
                'teachers_count' => $c->teachers->count(),
            ])
        );
    }

    public function getStudentsByClass($classId)
    {
        return response()->json(
            DB::table('class_user')->join('users', 'users.id', '=', 'class_user.user_id')
                ->where('class_user.class_model_id', $classId)->where('class_user.role', 'student')
                ->select('users.id', 'users.username', 'users.email')->get()
        );
    }

    public function getTeachersByClass($classId)
    {
        return response()->json(
            DB::table('class_user')->join('users', 'users.id', '=', 'class_user.user_id')
                ->where('class_user.class_model_id', $classId)->where('class_user.role', 'teacher')
                ->select('users.id', 'users.username', 'users.email')->get()
        );
    }
}