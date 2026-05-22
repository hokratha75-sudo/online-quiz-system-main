<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Major;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentReportController extends Controller
{
    public function getMajors(Department $department)
    {
        return response()->json($department->majors()->select('id', 'name')->get());
    }

    public function getClasses(Major $major)
    {
        return response()->json($major->classes()->select('id', 'name')->get());
    }

    public function exportStudentAcademicReport(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'major_id' => 'nullable|exists:majors,id',
            'class_id' => 'nullable|exists:class_models,id',
            'include_scores' => 'nullable|boolean',
            'include_status' => 'nullable|boolean',
        ]);

        $query = User::query()
            ->where('role_id', 3) // Assuming 3 is Student
            ->with(['department', 'major', 'classes', 'results' => function ($q) {
                $q->select('user_id', 'score', 'is_passed');
            }])
            ->when($request->filled('department_id'), fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->filled('major_id'), fn($q) => $q->where('major_id', $request->major_id))
            ->when($request->filled('class_id'), fn($q) => $q->whereHas('classes', fn($query) => $query->where('class_models.id', $request->class_id)));

        $students = $query->get()->map(function ($student) use ($request) {
            $totalQuizzes = $student->results->count();
            $avgScore = $totalQuizzes > 0 ? $student->results->avg('score') : 0;
            
            $passedQuizzes = $student->results->where('is_passed', true)->count();
            $overallStatus = ($totalQuizzes > 0 && ($passedQuizzes / $totalQuizzes) >= 0.6) ? 'Passed' : 'Failed';

            $data = [
                'Student ID' => str_pad($student->id, 5, '0', STR_PAD_LEFT),
                'Full Name' => $student->first_name . ' ' . $student->last_name,
                'Department' => $student->department?->name ?? 'N/A',
                'Major' => $student->major?->name ?? 'N/A',
                'Class' => $student->classes->pluck('name')->join(', ') ?: 'N/A',
            ];

            if ($request->boolean('include_scores')) {
                $data['Total Quizzes Attempted'] = $totalQuizzes;
                $data['Average Score (%)'] = round($avgScore, 2) . '%';
            }

            if ($request->boolean('include_status')) {
                $data['Overall Status'] = $totalQuizzes > 0 ? $overallStatus : 'No Attempts';
            }

            return $data;
        });

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=student_academic_report_" . date('Y_m_d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = count($students) > 0 ? array_keys($students->first()) : [];

        $callback = function() use($students, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($students as $row) {
                fputcsv($file, array_values($row));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
