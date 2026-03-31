<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Http\Requests\Admin\MajorRequest;
use Illuminate\Http\Request;
use App\Traits\CSVExportTrait;


class MajorController extends Controller
{
    use CSVExportTrait;

    /**
     * Display a listing of the majors.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'majors');
        $search = $request->get('search');
        $userRole = 'admin';
        $dashboardTitle = 'Department Management';

        $counts = [
            'departments' => \App\Models\Department::whereNotNull('code')->count(),
            'majors' => \App\Models\Major::count(),
            'classes' => \App\Models\ClassModel::whereHas('major.department', fn ($q) => $q->whereNotNull('code'))->count(),
            'subjects' => \App\Models\Subject::whereHas('major.department', fn ($q) => $q->whereNotNull('code'))->count(),
        ];

        // Data for Modals (dropdowns)
        $departments = \App\Models\Department::whereNotNull('code')->orderBy('department_name')->get();
        $majors_all = \App\Models\Major::whereHas('department', fn ($q) => $q->whereNotNull('code'))->orderBy('name')->get();

        $items = null;
        switch ($tab) {
            case 'majors':
                $query = \App\Models\Major::with('department')
                    ->whereHas('department', fn ($q) => $q->whereNotNull('code'))
                    ->withCount(['classes', 'subjects']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
                $items = $query->latest()->paginate(10);
                break;
            case 'classes':
                $query = \App\Models\ClassModel::with('major.department')
                    ->whereHas('major.department', fn ($q) => $q->whereNotNull('code'))
                    ->withCount(['students', 'subjects']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
                $items = $query->latest()->paginate(10);
                break;
            case 'subjects':
                $query = \App\Models\Subject::with('major.department')
                    ->whereHas('major.department', fn ($q) => $q->whereNotNull('code'))
                    ->withCount(['classes', 'quizzes']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('subject_name', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
                $items = $query->latest()->paginate(10);
                break;
            default: // departments
                $query = \App\Models\Department::whereNotNull('code')->withCount(['majors', 'classes', 'subjects']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('department_name', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
                }
                $items = $query->latest()->paginate(10);
                break;
        }

        return view('admin.majors.index', [
            'items' => $items,
            'tab' => $tab,
            'dashboardTitle' => $dashboardTitle,
            'userRole' => $userRole,
            'majors_count' => $counts['majors'],
            'classes_count' => $counts['classes'],
            'subjects_count' => $counts['subjects'],
            'departments_count' => $counts['departments'],
            'departments' => $departments,
            'majors_all' => $majors_all,
        ]);
    }

    /**
     * Store a newly created major in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:majors',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        Major::create($validated);

        return redirect()->route('admin.majors.index', ['tab' => 'majors'])
            ->with('success', 'Major created successfully.');
    }

    /**
     * Update the specified major in storage.
     */
    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:majors,code,' . $major->id,
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        $major->update($validated);

        return redirect()->route('admin.majors.index', ['tab' => 'majors'])
            ->with('success', 'Major updated successfully.');
    }

    /**
     * Remove the specified major from storage.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Major::whereIn('id', $request->ids)->delete();
        return redirect()->back()->with('success', 'Selected items deleted successfully.');
    }

    public function destroy(Major $major)
    {
        $major->delete();

        return redirect()->route('admin.majors.index', ['tab' => 'majors'])
            ->with('success', 'Major deleted successfully.');
    }

    /**
     * Restore a soft deleted major.
     */
    public function restore($id)
    {
        $major = Major::withTrashed()->findOrFail($id);
        $major->restore();

        return redirect()->route('admin.majors.index', ['tab' => 'majors'])
            ->with('success', 'Major restored successfully.');
    }

    /**
     * Display the full academic hierarchy.
     */
    public function hierarchy()
    {
        $departments = \App\Models\Department::with(['majors.classes.subjects' => function($query) {
            $query->withCount('quizzes');
        }])->get();

        $dashboardTitle = 'Department Hierarchy';
        $userRole = 'admin';

        return view('admin.majors.hierarchy', compact('departments', 'dashboardTitle', 'userRole'));
    }

    /**
     * Show detail for a single major.
     */
    public function show(Major $major)
    {
        $major->load([
            'department',
            'classes' => function ($q) {
                $q->withCount('students')->with('subjects');
            },
            'subjects' => function ($q) {
                $q->withCount(['classes', 'quizzes'])->with('quizzes')->orderBy('id');
            },
        ]);

        $dashboardTitle = 'Major Detail';
        $userRole = 'admin';

        return view('admin.majors.show', compact('major', 'dashboardTitle', 'userRole'));
    }

    public function export(Request $request)
    {
        $tab = $request->get('tab', 'majors');
        $data = [];
        $headers = [];
        $filename = "{$tab}_data.csv";

        switch ($tab) {
            case 'majors':
                $headers = ['#', 'Code', 'Major', 'Department', 'Total Classes', 'Total Subjects'];
                $items = \App\Models\Major::with('department')->withCount(['classes', 'subjects'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->code,
                        $item->name,
                        $item->department->department_name ?? 'N/A',
                        $item->classes_count,
                        $item->subjects_count
                    ];
                }
                break;
            case 'classes':
                $headers = ['#', 'Code', 'Class', 'Major', 'Year', 'Total Students', 'Total Subjects'];
                $items = \App\Models\ClassModel::with('major')->withCount(['students', 'subjects'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->code,
                        $item->name,
                        $item->major->name ?? 'N/A',
                        $item->academic_year ?? 'N/A',
                        $item->students_count,
                        $item->subjects_count
                    ];
                }
                break;
            case 'subjects':
                $headers = ['#', 'Code', 'Subject', 'Major', 'Credits', 'Total Classes', 'Total Quizzes'];
                $items = \App\Models\Subject::with('major')->withCount(['classes', 'quizzes'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->code,
                        $item->subject_name,
                        $item->major->name ?? 'N/A',
                        $item->credits ?? 3,
                        $item->classes_count,
                        $item->quizzes_count
                    ];
                }
                break;
            default: // departments
                $headers = ['#', 'Code', 'Department Name', 'Total Majors', 'Total Classes', 'Total Subjects'];
                $items = \App\Models\Department::withCount(['majors', 'classes', 'subjects'])->get();
                foreach ($items as $index => $item) {
                    $data[] = [
                        $index + 1,
                        $item->code,
                        $item->department_name,
                        $item->majors_count,
                        $item->classes_count,
                        $item->subjects_count
                    ];
                }
                break;
        }

        return $this->exportCSV($headers, $data, $filename);
    }
}
