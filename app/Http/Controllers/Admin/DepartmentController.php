<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Traits\CSVExportTrait;


class DepartmentController extends Controller
{
    use CSVExportTrait;

    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Department::withCount(['majors', 'classes', 'subjects']);
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('department_name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }
        
        $departments = $query->latest()->paginate(10);
        
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required_without:department_name|string|max:100|unique:departments,department_name',
            'department_name' => 'required_without:name|string|max:100|unique:departments,department_name',
            'code' => 'nullable|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
        ]);

        $name = $request->input('name', $request->department_name);

        Department::create([
            'department_name' => $name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department added successfully.');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required_without:department_name|string|max:100|unique:departments,department_name,' . $department->id,
            'department_name' => 'required_without:name|string|max:100|unique:departments,department_name,' . $department->id,
            'code' => 'nullable|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $name = $request->input('name', $request->department_name);

        $department->update([
            'department_name' => $name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Department::whereIn('id', $request->ids)->delete();
        return redirect()->route('admin.departments.index')
            ->with('success', 'Selected departments deleted successfully.');
    }

    public function restore($id)
    {
        $department = Department::withTrashed()->findOrFail($id);
        $department->restore();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department restored successfully.');
    }

    public function show(Department $department)
    {
        $department->load([
            'majors' => function ($query) {
                $query->with([
                    'classes' => function ($classQuery) {
                        $classQuery->withCount('students')->orderBy('id');
                    },
                    'subjects' => function ($subjectQuery) {
                        $subjectQuery->withCount('quizzes')->orderBy('id');
                    },
                ])->orderBy('id');
            },
        ])->loadCount(['majors', 'classes', 'subjects']);

        $dashboardTitle = 'Department Detail';
        $userRole = 'admin';

        return view('admin.departments.show', compact('department', 'dashboardTitle', 'userRole'));
    }

    public function export()
    {
        $headers = ['#', 'Code', 'Department Name', 'Total Majors', 'Total Classes', 'Total Subjects'];
        $departments = Department::withCount(['majors', 'classes', 'subjects'])->get();
        
        $data = [];
        foreach ($departments as $index => $dept) {
            $data[] = [
                $index + 1,
                $dept->code ?? 'DPT' . str_pad($dept->id, 3, '0', STR_PAD_LEFT),
                $dept->department_name,
                $dept->majors_count,
                $dept->classes_count,
                $dept->subjects_count
            ];
        }

        return $this->exportCSV($headers, $data, 'departments_data.csv');
    }
}
