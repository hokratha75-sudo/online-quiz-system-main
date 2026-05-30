<?php

namespace App\Services;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Department;
use App\Models\Major;
use App\Models\EnrollmentHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollmentService
{
    protected $validator;

    public function __construct(EnrollmentValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Complete enrollment for a department
     */
    public function enrollDepartment(
        Department $department,
        array $studentIds = [],
        array $teacherIds = [],
        array $subjectIds = [],
        int $adminId = null
    ): array {
        try {
            DB::beginTransaction();

            // Validate enrollment data
            if (!$this->validator->validateDepartmentEnrollment($department, $studentIds, $teacherIds, $subjectIds)) {
                return [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors(),
                    'warnings' => $this->validator->getWarnings(),
                ];
            }

            // Get or create classes
            $classes = $this->getOrCreateClasses($department);

            // Enroll students
            $studentStats = $this->enrollStudents($classes, $studentIds, $department->id, $adminId);

            // Enroll teachers
            $teacherStats = $this->enrollTeachers($classes, $teacherIds, $department->id, $adminId);

            // Assign subjects
            $subjectStats = $this->assignSubjects($classes, $subjectIds, $department->id, $adminId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Enrollment completed successfully',
                'statistics' => [
                    'students' => $studentStats,
                    'teachers' => $teacherStats,
                    'subjects' => $subjectStats,
                    'classes_count' => count($classes),
                ],
                'warnings' => $this->validator->getWarnings(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enrollment error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Enrollment failed: ' . $e->getMessage(),
                'errors' => [$e->getMessage()],
            ];
        }
    }

    /**
     * Get or create classes for department
     */
    protected function getOrCreateClasses(Department $department): array
    {
        // Return an array of ClassModel instances so downstream code can call relationship methods
        $classes = ClassModel::whereIn('major_id', $department->majors()->pluck('id'))->get()->all();

        if (empty($classes)) {
            $major = $department->majors()->first();
            if (!$major) {
                $major = Major::create([
                    'name' => $department->department_name . ' - Default Major',
                    'code' => strtoupper(substr($department->department_name, 0, 3)) . '_DM',
                    'department_id' => $department->id,
                ]);
            }

            $class = ClassModel::create([
                'class_name' => $department->department_name . ' - Main Class',
                'code' => strtoupper(substr($department->department_name, 0, 3)) . '001',
                'major_id' => $major->id,
                'academic_year' => date('Y'),
            ]);

            $classes = [$class];
            Log::info('Created default class for department', ['class_id' => $class->id, 'department_id' => $department->id]);
        }

        return $classes;
    }

    /**
     * Enroll students in classes
     */
    protected function enrollStudents(array $classes, array $studentIds, int $departmentId, int $adminId = null): array
    {
        $stats = [
            'total' => count($studentIds),
            'enrolled' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        if (empty($studentIds)) {
            // Remove all student enrollments
            foreach ($classes as $class) {
                $class->students()->detach();
            }
            $stats['unenrolled'] = true;
            return $stats;
        }

        foreach ($classes as $class) {
            // Get current students
            $currentStudentIds = $class->students()->pluck('user_id')->toArray();

            // Find new and removed students
            $toAdd = array_diff($studentIds, $currentStudentIds);
            $toRemove = array_diff($currentStudentIds, $studentIds);

            // Remove students
            if (!empty($toRemove)) {
                $class->students()->detach($toRemove);
                foreach ($toRemove as $studentId) {
                    EnrollmentHistory::log(
                        'unenrolled',
                        'student',
                        $departmentId,
                        $studentId,
                        $class->id,
                        null,
                        'Removed from enrollment',
                        $adminId
                    );
                }
            }

            // Add students
            if (!empty($toAdd)) {
                $syncData = collect($toAdd)->mapWithKeys(function ($id) {
                    return [$id => ['role' => 'student']];
                })->toArray();

                $class->users()->attach($syncData);
                $stats['enrolled'] += count($toAdd);

                foreach ($toAdd as $studentId) {
                    EnrollmentHistory::log(
                        'enrolled',
                        'student',
                        $departmentId,
                        $studentId,
                        $class->id,
                        null,
                        'Enrolled in class',
                        $adminId
                    );
                }
            }
        }

        return $stats;
    }

    /**
     * Enroll teachers in classes
     */
    protected function enrollTeachers(array $classes, array $teacherIds, int $departmentId, int $adminId = null): array
    {
        $stats = [
            'total' => count($teacherIds),
            'enrolled' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        if (empty($teacherIds)) {
            // Remove all teacher enrollments
            foreach ($classes as $class) {
                $class->users()
                    ->wherePivot('role', 'teacher')
                    ->detach();
            }
            $stats['unenrolled'] = true;
            return $stats;
        }

        foreach ($classes as $class) {
            // Get current teachers
            $currentTeacherIds = $class->users()
                ->wherePivot('role', 'teacher')
                ->pluck('user_id')
                ->toArray();

            // Find new and removed teachers
            $toAdd = array_diff($teacherIds, $currentTeacherIds);
            $toRemove = array_diff($currentTeacherIds, $teacherIds);

            // Remove teachers
            if (!empty($toRemove)) {
                $class->users()
                    ->whereIn('user_id', $toRemove)
                    ->detach();

                foreach ($toRemove as $teacherId) {
                    EnrollmentHistory::log(
                        'unenrolled',
                        'teacher',
                        $departmentId,
                        $teacherId,
                        $class->id,
                        null,
                        'Removed from teaching',
                        $adminId
                    );
                }
            }

            // Add teachers
            if (!empty($toAdd)) {
                $syncData = collect($toAdd)->mapWithKeys(function ($id) {
                    return [$id => ['role' => 'teacher']];
                })->toArray();

                $class->users()->attach($syncData);
                $stats['enrolled'] += count($toAdd);

                foreach ($toAdd as $teacherId) {
                    EnrollmentHistory::log(
                        'enrolled',
                        'teacher',
                        $departmentId,
                        $teacherId,
                        $class->id,
                        null,
                        'Assigned to teach class',
                        $adminId
                    );
                }
            }
        }

        return $stats;
    }

    /**
     * Assign subjects to classes
     */
    protected function assignSubjects(array $classes, array $subjectIds, int $departmentId, int $adminId = null): array
    {
        $stats = [
            'total' => count($subjectIds),
            'assigned' => 0,
            'updated' => 0,
        ];

        foreach ($classes as $class) {
            $oldSubjects = $class->subjects()->pluck('id')->toArray();
            $class->subjects()->sync($subjectIds);
            
            $newSubjects = $subjectIds;
            $added = array_diff($newSubjects, $oldSubjects);
            $removed = array_diff($oldSubjects, $newSubjects);

            $stats['assigned'] += count($added);
            $stats['updated'] += count($removed);

            foreach ($added as $subjectId) {
                EnrollmentHistory::log(
                    'subject_assigned',
                    'subject',
                    $departmentId,
                    null,
                    $class->id,
                    $subjectId,
                    'Subject assigned to class',
                    $adminId
                );
            }

            foreach ($removed as $subjectId) {
                EnrollmentHistory::log(
                    'subject_removed',
                    'subject',
                    $departmentId,
                    null,
                    $class->id,
                    $subjectId,
                    'Subject removed from class',
                    $adminId
                );
            }
        }

        return $stats;
    }

    /**
     * Get enrollment statistics
     */
    public function getEnrollmentStats(Department $department): array
    {
        $classes = ClassModel::whereIn('major_id', $department->majors()->pluck('id'))->get();

        $totalStudents = 0;
        $totalTeachers = 0;
        $totalSubjects = 0;

        foreach ($classes as $class) {
            $totalStudents += $class->students()->count();
            $totalTeachers += $class->users()->wherePivot('role', 'teacher')->count();
            $totalSubjects += $class->subjects()->count();
        }

        return [
            'classes_count' => $classes->count(),
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_subjects' => $totalSubjects,
            'average_students_per_class' => $classes->count() > 0 ? round($totalStudents / $classes->count(), 2) : 0,
            'average_subjects_per_class' => $classes->count() > 0 ? round($totalSubjects / $classes->count(), 2) : 0,
        ];
    }

    /**
     * Get enrollment history
     */
    public function getEnrollmentHistory(Department $department, int $limit = 50): Collection
    {
        return EnrollmentHistory::where('department_id', $department->id)
            ->with(['user', 'class', 'subject', 'admin'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Export enrollment data to CSV
     */
    public function exportToCSV(Department $department): string
    {
        $classes = ClassModel::whereIn('major_id', $department->majors()->pluck('id'))
            ->with(['users', 'subjects'])
            ->get();

        $csv = "Class Name,User Type,Username,Email,Subject\n";

        foreach ($classes as $class) {
            // Export students
            foreach ($class->students as $student) {
                foreach ($class->subjects as $subject) {
                    $csv .= "\"{$class->class_name}\",Student,{$student->username},{$student->email},{$subject->subject_name}\n";
                }
            }

            // Export teachers
            foreach ($class->users()->wherePivot('role', 'teacher')->get() as $teacher) {
                foreach ($class->subjects as $subject) {
                    $csv .= "\"{$class->class_name}\",Teacher,{$teacher->username},{$teacher->email},{$subject->subject_name}\n";
                }
            }
        }

        return $csv;
    }

    /**
     * Import enrollment from CSV
     */
    public function importFromCSV(Department $department, string $csvContent): array
    {
        $lines = array_filter(array_map('trim', explode("\n", $csvContent)));
        array_shift($lines); // Remove header

        $stats = [
            'processed' => 0,
            'students_enrolled' => 0,
            'teachers_enrolled' => 0,
            'subjects_assigned' => 0,
            'errors' => [],
        ];

        $studentIds = [];
        $teacherIds = [];
        $subjectIds = [];

        foreach ($lines as $line) {
            if (empty($line)) continue;

            $data = str_getcsv($line);
            if (count($data) < 5) {
                $stats['errors'][] = "Invalid line format: $line";
                continue;
            }

            $className = $data[0];
            $userType = $data[1];
            $username = $data[2];
            $email = $data[3];
            $subjectName = $data[4];

            // Find user
            $user = User::where('username', $username)->orWhere('email', $email)->first();
            if (!$user) {
                $stats['errors'][] = "User not found: $username";
                continue;
            }

            // Add to appropriate list
            if ($userType === 'Student' && (int)$user->role_id === 3) {
                $studentIds[] = $user->id;
                $stats['students_enrolled']++;
            } elseif ($userType === 'Teacher' && (int)$user->role_id === 2) {
                $teacherIds[] = $user->id;
                $stats['teachers_enrolled']++;
            }

            // Find subject
            $subject = Subject::where('subject_name', $subjectName)->first();
            if ($subject) {
                $subjectIds[] = $subject->id;
                $stats['subjects_assigned']++;
            }

            $stats['processed']++;
        }

        // Perform enrollment
        $enrollmentResult = $this->enrollDepartment(
            $department,
            array_unique($studentIds),
            array_unique($teacherIds),
            array_unique($subjectIds)
        );

        return array_merge($stats, $enrollmentResult);
    }
}
