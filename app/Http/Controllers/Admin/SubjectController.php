<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Subject;
use App\Models\Department;
use App\Models\ClassModel;
use App\Http\Requests\StoreSubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Traits\CSVExportTrait;


class SubjectController extends Controller
{
    use CSVExportTrait;

    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Subject::with(['major.department', 'classes'])->withCount(['classes', 'quizzes']);
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subject_name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        
        $subjects = $query->latest()->paginate(10);
        $departments = Department::whereNotNull('code')->orderBy('department_name')->get();
        $majors = Major::orderBy('name')->get();
        $classes = ClassModel::orderBy('name')->get();
        
        return view('admin.subjects.index', compact('subjects', 'departments', 'majors', 'classes'));
    }

    public function store(StoreSubjectRequest $request)
    {
        try {
            $major = $request->filled('major_id') ? Major::find($request->major_id) : null;
            $subjectName = $request->input('name', $request->subject_name);

            $subject = Subject::create([
                'subject_name' => $subjectName,
                'department_id' => $request->department_id ?? $major?->department_id,
                'major_id' => $request->major_id,
                'code' => $request->code,
                'credits' => $request->credits ?? 3,
                'created_by' => Auth::id(),
            ]);

            if ($request->has('class_ids')) {
                $subject->classes()->sync($request->class_ids);
            }

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject added successfully.');
        } catch (\Exception $e) {
            Log::error('Subject creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'A system error occurred.');
        }
    }

    public function update(StoreSubjectRequest $request, Subject $subject)
    {
        try {
            $major = $request->filled('major_id') ? Major::find($request->major_id) : null;
            $subjectName = $request->input('name', $request->subject_name);

            $subject->update([
                'subject_name' => $subjectName,
                'department_id' => $request->department_id ?? $major?->department_id,
                'major_id' => $request->major_id,
                'code' => $request->code,
                'credits' => $request->credits ?? 3,
            ]);

            if ($request->has('class_ids')) {
                $subject->classes()->sync($request->class_ids);
            } else {
                $subject->classes()->detach();
            }

            return redirect()->route('admin.subjects.index')
                ->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            Log::error('Subject update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'A system error occurred.');
        }
    }

    public function bulkDelete(\Illuminate\Http\Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        \App\Models\Subject::whereIn('id', $request->ids)->delete();
        return redirect()->back()->with('success', 'Selected items deleted successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function restore($id)
    {
        $subject = Subject::withTrashed()->findOrFail($id);
        $subject->restore();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject restored successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load([
            'department',
            'major.department',
            'classes' => function ($query) {
                $query->withCount('students')->with('major');
            },
            'quizzes.creator',
        ]);

        $dashboardTitle = 'Course Detail';
        $userRole = 'admin';

        return view('admin.subjects.show', compact('subject', 'dashboardTitle', 'userRole'));
    }

    public function export()
    {
        $headers = ['#', 'Code', 'Subject Name', 'Major', 'Credits', 'Total Classes', 'Total Quizzes'];
        $subjects = Subject::with('major')->withCount(['classes', 'quizzes'])->get();
        
        $data = [];
        foreach ($subjects as $index => $subject) {
            $data[] = [
                $index + 1,
                $subject->code ?? 'SUB' . str_pad($subject->id, 3, '0', STR_PAD_LEFT),
                $subject->subject_name,
                $subject->major->name ?? 'N/A',
                $subject->credits ?? 3,
                $subject->classes_count,
                $subject->quizzes_count
            ];
        }

        return $this->exportCSV($headers, $data, 'subjects_data.csv');
    }

    public function myCourses(Request $request)
    {
        $user = Auth::user();
        $isStudent = (int)$user->role_id === 3;
        
        $query = Subject::with(['major.department', 'classes' => function($q) {
            $q->withCount('students');
        }]);
            
        // Apply Search Filter
        $search = $request->get('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('subjects.subject_name', 'LIKE', "%{$search}%")
                  ->orWhere('subjects.code', 'LIKE', "%{$search}%");
            });
        }

        // Admins see everything, students/teachers see their assigned courses only
        if ((int)$user->role_id !== 1) {
            $query->where(function($q) use ($user) {
                // 1. Check Class-based enrollment (pivot table)
                $q->whereHas('classes.users', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
                
                // 2. Check Department-based enrollment (Master Enrollment feature)
                if ($user->department_id) {
                    $q->orWhere('subjects.department_id', $user->department_id);
                }
            });
        }
            
        $subjects = $query->latest()->paginate(12)->appends(['search' => $search]);
        $dashboardTitle = $isStudent ? 'My Course' : 'Manage Courses';
        
        return view('courses.index', compact('subjects', 'dashboardTitle', 'isStudent'));
    }

    public function showCourse(Subject $subject)
    {
        $subject->load([
            'department',
            'major.department',
            'classes' => function ($query) {
                $query->withCount('students')->with('major');
            },
            'quizzes.creator',
            'materials.creator',
        ]);

        $dashboardTitle = 'Course Detail';
        $userRole = Auth::user()->role_id == 1 ? 'admin' : (Auth::user()->role_id == 2 ? 'teacher' : 'student');

        return view('courses.show', compact('subject', 'dashboardTitle', 'userRole'));
    }
}
