<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Major;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Result;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $majors = Major::all();
        $classes = ClassModel::all();
        $subjects = Subject::all();
        
        $dashboardTitle = 'Advanced Reporting';
        $userRole = 'admin';

        return view('admin.reports.index', compact('departments', 'majors', 'classes', 'subjects', 'dashboardTitle', 'userRole'));
    }

    public function generate(Request $request)
    {
        $type = $request->input('type'); // class, major, department, subject
        $id = $request->input('id');
        
        $query = Result::with(['user', 'quiz.subject.major.department']);

        // Apply Filters (Define what data we want)
        $status = $request->input('status', 'all');
        if ($status === 'passed') {
            $query->where('passed', true);
        } elseif ($status === 'failed') {
            $query->where('passed', false);
        }

        switch ($type) {
            case 'class':
                $class = ClassModel::findOrFail($id);
                $userIds = \DB::table('class_user')->where('class_model_id', $id)->pluck('user_id');
                $query->whereIn('user_id', $userIds);
                $title = "Student Score Report - Class: " . $class->name;
                break;
            case 'major':
                $major = Major::findOrFail($id);
                $query->whereHas('quiz.subject', function($q) use ($id) {
                    $q->where('major_id', $id);
                });
                $title = "Academic Report - Major: " . $major->name;
                break;
            case 'department':
                $dept = Department::findOrFail($id);
                $query->whereHas('quiz.subject.major', function($q) use ($id) {
                    $q->where('department_id', $id);
                });
                $title = "Faculty Report - Department: " . $dept->department_name;
                break;
            case 'subject':
                $subject = Subject::findOrFail($id);
                $query->whereHas('quiz', function($q) use ($id) {
                    $q->where('subject_id', $id);
                });
                $title = "Subject Performance - " . $subject->subject_name;
                break;
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }

        // Apply Sorting
        $sort = $request->input('sort', 'date_desc');
        switch($sort) {
            case 'name_asc':
                $query->join('users', 'results.user_id', '=', 'users.id')
                      ->orderBy('users.username', 'asc')
                      ->select('results.*');
                break;
            case 'score_desc':
                $query->orderBy('score', 'desc');
                break;
            case 'score_asc':
                $query->orderBy('score', 'asc');
                break;
            case 'date_desc':
            default:
                $query->latest('completed_at');
                break;
        }

        $results = $query->get();

        // Handle Excel Export (Clean data only as requested)
        if ($request->input('export') === 'excel' && !$request->has('listname')) {
            $fileName = str_replace(' ', '_', $title) . '_' . date('Ymd_His') . '.csv';
            $headers = [
                "Content-type"        => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($results) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                
                fputcsv($file, ['ID', 'Student Name', 'Quiz/Subject', 'Score (%)', 'Result', 'Date']);

                foreach ($results as $index => $row) {
                    fputcsv($file, [
                        $index + 1,
                        $row->user?->username ?? 'Unknown',
                        $row->quiz?->title ?? 'N/A',
                        round($row->score, 1) . '%',
                        $row->passed ? 'Passed' : 'Failed',
                        $row->completed_at ? $row->completed_at->format('Y-m-d H:i') : 'N/A'
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
        
        // Handle Listname specific logic if requested
        if ($request->has('listname')) {
            return $this->generateListname($type, $id, $title);
        }

        return view('admin.reports.print', compact('results', 'title', 'type'));
    }

    private function generateListname($type, $id, $title)
    {
        if ($type !== 'class') {
            return redirect()->back()->with('error', 'Listnames are only available for classes.');
        }

        $class = ClassModel::findOrFail($id);
        $title = "Student List - Class: " . $class->name;
        
        $query = $class->users()->where('role_id', 3);

        // Apply Gender Filter
        $gender = request()->input('gender', 'all');
        if ($gender !== 'all') {
            $query->where('sex', $gender);
        }

        // Apply Sorting for Listname
        $sort = request()->input('sort', 'name_asc');
        if ($sort === 'name_asc') {
            $query->orderBy('username', 'asc');
        }

        $users = $query->get();

        // Handle Excel Export for Listname
        if (request()->input('export') === 'excel') {
            $fileName = str_replace(' ', '_', $title) . '_' . date('Ymd_His') . '.csv';
            $headers = [
                "Content-type"        => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($users, $class) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                
                fputcsv($file, ['Class', $class->name]);
                fputcsv($file, ['Total Students', $users->count()]);
                fputcsv($file, []); 

                fputcsv($file, ['No.', 'Full Name', 'Gender', 'Email', 'Phone']);

                foreach ($users as $index => $user) {
                    fputcsv($file, [
                        $index + 1,
                        $user->username,
                        $user->sex ?? '---',
                        $user->email,
                        $user->phone ?? ''
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.reports.listname', compact('users', 'title', 'class'));
    }
}
