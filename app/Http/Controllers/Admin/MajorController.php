<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Department;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Http\Requests\Admin\MajorRequest;
use Illuminate\Http\Request;
use App\Traits\CSVExportTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MajorController extends Controller
{
    use CSVExportTrait;

    /**
     * Display a listing of the majors with tab navigation.
     */
    public function index(Request $request)
    {
        // Default to 'majors' so the Majors page shows majors list by default
        $tab = $request->get('tab', 'majors');
        $search = $request->get('search');
        $userRole = 'admin';
        $dashboardTitle = 'Academic Structure Management';

        // Get counts for all sections
        $counts = [
            'departments' => Department::count(),
            'majors' => Major::count(),
            'classes' => ClassModel::count(),
            'subjects' => Subject::count(),
        ];

        // Data for Modals (dropdowns)
        $departments = Department::orderBy('department_name')->get();
        $majors_all = Major::with('department')->orderBy('name')->get();

        $items = null;
        
        switch ($tab) {
            case 'majors':
                $query = Major::with('department')
                    ->withCount(['classes', 'subjects']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('code', 'LIKE', "%{$search}%")
                          ->orWhereHas('department', function ($dq) use ($search) {
                              $dq->where('department_name', 'LIKE', "%{$search}%");
                          });
                    });
                }
                $items = $query->latest()->paginate(10);
                break;
                
            case 'classes':
                $query = ClassModel::with('major.department')
                    ->withCount(['students', 'subjects']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('code', 'LIKE', "%{$search}%")
                          ->orWhereHas('major', function ($mq) use ($search) {
                              $mq->where('name', 'LIKE', "%{$search}%");
                          });
                    });
                }
                $items = $query->latest()->paginate(10);
                break;
                
            case 'subjects':
                $query = Subject::with('major.department')
                    ->withCount(['classes', 'quizzes']);
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('subject_name', 'LIKE', "%{$search}%")
                          ->orWhere('code', 'LIKE', "%{$search}%")
                          ->orWhereHas('major', function ($mq) use ($search) {
                              $mq->where('name', 'LIKE', "%{$search}%");
                          });
                    });
                }
                $items = $query->latest()->paginate(10);
                break;
                
            default: // departments
                $query = Department::withCount(['majors', 'classes', 'subjects']);
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
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created major in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:majors,code',
                'department_id' => 'required|exists:departments,id',
                'description' => 'nullable|string|max:1000',
            ]);

            $major = Major::create($validated);

            return redirect()->route('admin.majors.index', ['tab' => 'majors'])
                ->with('success', "Major '{$major->name}' created successfully.");

        } catch (\Exception $e) {
            Log::error('Major creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create major. Please try again.');
        }
    }

    /**
     * Display the specified major.
     */
    public function show(Major $major)
    {
        try {
            $major->load([
                'department',
                'classes' => function ($q) {
                    $q->withCount('students')
                      ->with('subjects')
                      ->orderBy('name');
                },
                'subjects' => function ($q) {
                    $q->withCount(['classes', 'quizzes'])
                      ->with('quizzes')
                      ->orderBy('subject_name');
                },
            ]);

            // Calculate statistics
            $stats = [
                'total_students' => $major->classes->sum('students_count'),
                'total_quizzes' => $major->subjects->sum('quizzes_count'),
                'total_classes' => $major->classes->count(),
                'total_subjects' => $major->subjects->count(),
            ];

            $dashboardTitle = "Major: {$major->name}";
            $userRole = 'admin';

            return view('admin.majors.show', compact('major', 'stats', 'dashboardTitle', 'userRole'));

        } catch (\Exception $e) {
            Log::error('Major view failed: ' . $e->getMessage());
            return redirect()->route('admin.majors.index', ['tab' => 'majors'])
                ->with('error', 'Failed to load major details.');
        }
    }

    /**
     * Update the specified major in storage.
     */
    public function update(Request $request, Major $major)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:majors,code,' . $major->id,
                'department_id' => 'required|exists:departments,id',
                'description' => 'nullable|string|max:1000',
            ]);

            $oldName = $major->name;
            $major->update($validated);

            return redirect()->route('admin.majors.index', ['tab' => 'majors'])
                ->with('success', "Major '{$oldName}' updated to '{$major->name}' successfully.");

        } catch (\Exception $e) {
            Log::error('Major update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update major. Please try again.');
        }
    }

    /**
     * Remove the specified major from storage.
     */
    public function destroy(Major $major)
    {
        try {
            // Check if major has related records
            $hasClasses = $major->classes()->exists();
            $hasSubjects = $major->subjects()->exists();
            
            if ($hasClasses || $hasSubjects) {
                $warning = [];
                if ($hasClasses) $warning[] = $major->classes()->count() . ' class(es)';
                if ($hasSubjects) $warning[] = $major->subjects()->count() . ' subject(s)';
                
                return redirect()->back()
                    ->with('error', "Cannot delete '{$major->name}' because it has associated " . implode(' and ', $warning) . ". Please reassign or delete them first.");
            }
            
            $majorName = $major->name;
            $major->delete();

            return redirect()->route('admin.majors.index', ['tab' => 'majors'])
                ->with('success', "Major '{$majorName}' deleted successfully.");

        } catch (\Exception $e) {
            Log::error('Major deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete major. Please try again.');
        }
    }

    /**
     * Bulk delete selected majors.
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:majors,id'
            ]);

            $majors = Major::whereIn('id', $request->ids)->get();
            $deletedCount = 0;
            $failedCount = 0;
            $failedNames = [];

            foreach ($majors as $major) {
                if ($major->classes()->exists() || $major->subjects()->exists()) {
                    $failedCount++;
                    $failedNames[] = $major->name;
                } else {
                    $major->delete();
                    $deletedCount++;
                }
            }

            $message = "Deleted {$deletedCount} major(s) successfully.";
            if ($failedCount > 0) {
                $message .= " Skipped {$failedCount} major(s) with existing records: " . implode(', ', $failedNames);
                return redirect()->back()->with('warning', $message);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Bulk delete failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete selected items.');
        }
    }

    /**
     * Restore a soft deleted major.
     */
    public function restore($id)
    {
        try {
            $major = Major::withTrashed()->findOrFail($id);
            $major->restore();

            return redirect()->route('admin.majors.index', ['tab' => 'majors'])
                ->with('success', "Major '{$major->name}' restored successfully.");

        } catch (\Exception $e) {
            Log::error('Major restoration failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to restore major.');
        }
    }

    /**
     * Display soft deleted majors.
     */
    public function trashed()
    {
        $trashedMajors = Major::onlyTrashed()
            ->with('department')
            ->withCount(['classes', 'subjects'])
            ->latest()
            ->paginate(10);

        $dashboardTitle = 'Deleted Majors';
        $userRole = 'admin';
        $departments = Department::orderBy('department_name')->get();

        return view('admin.majors.trashed', compact('trashedMajors', 'dashboardTitle', 'userRole', 'departments'));
    }

    /**
     * Permanently delete a major.
     */
    public function forceDelete($id)
    {
        try {
            $major = Major::withTrashed()->findOrFail($id);
            $majorName = $major->name;
            
            // Check for related records before force delete
            if ($major->classes()->exists() || $major->subjects()->exists()) {
                return redirect()->back()
                    ->with('error', "Cannot permanently delete '{$majorName}' because it has associated records.");
            }
            
            $major->forceDelete();

            return redirect()->route('admin.majors.trashed')
                ->with('success', "Major '{$majorName}' permanently deleted.");

        } catch (\Exception $e) {
            Log::error('Force delete failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to permanently delete major.');
        }
    }

    /**
     * Display the full academic hierarchy.
     */
    public function hierarchy()
    {
        try {
            $departments = Department::with(['majors' => function($q) {
                $q->with(['classes' => function($cq) {
                    $cq->withCount('students')
                       ->orderBy('name');
                }, 'subjects' => function($sq) {
                    $sq->withCount('quizzes')
                       ->orderBy('subject_name');
                }])->orderBy('name');
            }])->get();

            // Calculate global statistics
            $globalStats = [
                'total_departments' => Department::count(),
                'total_majors' => Major::count(),
                'total_classes' => ClassModel::count(),
                'total_subjects' => Subject::count(),
                'total_students' => ClassModel::sum('students_count'),
            ];

            $dashboardTitle = 'Academic Hierarchy Overview';
            $userRole = 'admin';

            return view('admin.majors.hierarchy', compact('departments', 'globalStats', 'dashboardTitle', 'userRole'));

        } catch (\Exception $e) {
            Log::error('Hierarchy view failed: ' . $e->getMessage());
            return redirect()->route('admin.majors.index')
                ->with('error', 'Failed to load academic hierarchy.');
        }
    }

    /**
     * Export data to CSV.
     */
    public function export(Request $request)
    {
        try {
            $tab = $request->get('tab', 'majors');
            $data = [];
            $headers = [];
            $filename = "{$tab}_export_" . date('Y-m-d') . ".csv";

            switch ($tab) {
                case 'majors':
                    $headers = ['ID', 'Code', 'Major Name', 'Department', 'Total Classes', 'Total Subjects', 'Created At'];
                    $items = Major::with('department')->withCount(['classes', 'subjects'])->get();
                    foreach ($items as $index => $item) {
                        $data[] = [
                            $index + 1,
                            $item->code,
                            $item->name,
                            $item->department->department_name ?? 'N/A',
                            $item->classes_count,
                            $item->subjects_count,
                            $item->created_at->format('Y-m-d'),
                        ];
                    }
                    break;
                    
                case 'classes':
                    $headers = ['ID', 'Code', 'Class Name', 'Major', 'Academic Year', 'Total Students', 'Total Subjects'];
                    $items = ClassModel::with('major')->withCount(['students', 'subjects'])->get();
                    foreach ($items as $index => $item) {
                        $data[] = [
                            $index + 1,
                            $item->code ?? 'N/A',
                            $item->name,
                            $item->major->name ?? 'N/A',
                            $item->academic_year ?? 'N/A',
                            $item->students_count,
                            $item->subjects_count,
                        ];
                    }
                    break;
                    
                case 'subjects':
                    $headers = ['ID', 'Code', 'Subject Name', 'Major', 'Credits', 'Semester', 'Total Classes', 'Total Quizzes'];
                    $items = Subject::with('major')->withCount(['classes', 'quizzes'])->get();
                    foreach ($items as $index => $item) {
                        $data[] = [
                            $index + 1,
                            $item->code ?? 'N/A',
                            $item->subject_name,
                            $item->major->name ?? 'N/A',
                            $item->credits ?? 3,
                            $item->semester ?? 'N/A',
                            $item->classes_count,
                            $item->quizzes_count,
                        ];
                    }
                    break;
                    
                default: // departments
                    $headers = ['ID', 'Code', 'Department Name', 'Total Majors', 'Total Classes', 'Total Subjects', 'Created At'];
                    $items = Department::withCount(['majors', 'classes', 'subjects'])->get();
                    foreach ($items as $index => $item) {
                        $data[] = [
                            $index + 1,
                            $item->code ?? 'N/A',
                            $item->department_name,
                            $item->majors_count,
                            $item->classes_count,
                            $item->subjects_count,
                            $item->created_at->format('Y-m-d'),
                        ];
                    }
                    break;
            }

            if (empty($data)) {
                return redirect()->back()->with('warning', 'No data available to export.');
            }

            return $this->exportCSV($headers, $data, $filename);

        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export data.');
        }
    }

    /**
     * Get major statistics for dashboard.
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_majors' => Major::count(),
                'active_majors' => Major::whereNull('deleted_at')->count(),
                'deleted_majors' => Major::onlyTrashed()->count(),
                'majors_by_department' => Department::withCount('majors')
                    ->having('majors_count', '>', 0)
                    ->get()
                    ->map(fn($d) => [
                        'department' => $d->department_name,
                        'count' => $d->majors_count
                    ]),
                'recent_majors' => Major::with('department')
                    ->latest()
                    ->take(5)
                    ->get(),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Statistics fetch failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch statistics'], 500);
        }
    }
}