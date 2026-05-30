<?php

namespace App\Services;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Department;
use App\Models\Major;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollmentValidator
{
    protected array $errors = [];
    protected array $warnings = [];
    
    // Role constants for better maintainability
    public const ROLE_TEACHER = 2;
    public const ROLE_STUDENT = 3;

    /**
     * Validate student enrollment
     * 
     * @param int $studentId
     * @param array $classIds
     * @return bool
     */
    public function validateStudentEnrollment(int $studentId, array $classIds): bool
    {
        $this->resetValidation();
        
        $student = User::find($studentId);
        if (!$student) {
            $this->errors[] = "Student with ID {$studentId} not found";
            return false;
        }

        if ((int)$student->role_id !== self::ROLE_STUDENT) {
            $roleName = isset($student->role) && isset($student->role->role_name)
                ? $student->role->role_name
                : 'Unknown';
            $this->errors[] = "User {$student->username} is not a student (Role: {$roleName})";
            return false;
        }
        
        // Check if student is active
        if (property_exists($student, 'status') && $student->status !== 'active') {
            $this->warnings[] = "Student {$student->username} has status: {$student->status}";
        }

        // Check for duplicate enrollments and validate class existence
        foreach ($classIds as $classId) {
            $class = ClassModel::find($classId);
            if (!$class) {
                $this->errors[] = "Class ID {$classId} not found";
                continue;
            }

            if ($class->students()->where('user_id', $studentId)->exists()) {
                $this->warnings[] = "Student {$student->username} is already enrolled in {$class->class_name}";
            }
            
            // Check if class is full
            $currentEnrollment = $class->students()->count();
            $maxCapacity = $class->max_students ?? null;
            if ($maxCapacity && $currentEnrollment >= $maxCapacity) {
                $this->warnings[] = "Class {$class->class_name} has reached maximum capacity ({$maxCapacity})";
            }
        }

        return $this->isValid();
    }

    /**
     * Validate teacher enrollment
     * 
     * @param int $teacherId
     * @param array $classIds
     * @return bool
     */
    public function validateTeacherEnrollment(int $teacherId, array $classIds): bool
    {
        $this->resetValidation();

        $teacher = User::find($teacherId);
        if (!$teacher) {
            $this->errors[] = "Teacher with ID {$teacherId} not found";
            return false;
        }

        if ((int)$teacher->role_id !== self::ROLE_TEACHER) {
            $roleName = $teacher->role->role_name ?? 'Unknown';
            $this->errors[] = "User {$teacher->username} is not a teacher (Role: {$roleName})";
            return false;
        }

        foreach ($classIds as $classId) {
            $class = ClassModel::find($classId);
            if (!$class) {
                $this->errors[] = "Class ID {$classId} not found";
                continue;
            }

            if ($class->teachers()->where('user_id', $teacherId)->exists()) {
                $this->warnings[] = "Teacher {$teacher->username} is already teaching {$class->class_name}";
            }
        }

        return $this->isValid();
    }

    /**
     * Validate subject assignment to class
     * 
     * @param int $subjectId
     * @param int $classId
     * @return bool
     */
    public function validateSubjectAssignment(int $subjectId, int $classId): bool
    {
        $this->resetValidation();

        $subject = Subject::find($subjectId);
        if (!$subject) {
            $this->errors[] = "Subject ID {$subjectId} not found";
            return false;
        }

        $class = ClassModel::find($classId);
        if (!$class) {
            $this->errors[] = "Class ID {$classId} not found";
            return false;
        }

        // Check if subject is already assigned
        if ($class->subjects()->where('subject_id', $subjectId)->exists()) {
            $this->warnings[] = "Subject {$subject->subject_name} is already assigned to {$class->class_name}";
        }
        
        // Check if subject level is appropriate for the class
        if (property_exists($class, 'level') && property_exists($subject, 'level')) {
            if ($class->level !== $subject->level) {
                $this->warnings[] = "Subject level ({$subject->level}) does not match class level ({$class->level})";
            }
        }

        return $this->isValid();
    }

    /**
     * Validate department enrollment consistency
     * 
     * @param Department $department
     * @param array $studentIds
     * @param array $teacherIds
     * @param array $subjectIds
     * @return bool
     */
    public function validateDepartmentEnrollment(Department $department, array $studentIds, array $teacherIds, array $subjectIds): bool
    {
        $this->resetValidation();

        // Check students exist and are valid role
        foreach ($studentIds as $id) {
            $user = User::find($id);
            if (!$user) {
                $this->errors[] = "Student ID {$id} not found";
                continue;
            }
            if ((int)$user->role_id !== self::ROLE_STUDENT) {
                $this->errors[] = "{$user->username} is not a student";
            }
        }

        // Check teachers exist and are valid role
        foreach ($teacherIds as $id) {
            $user = User::find($id);
            if (!$user) {
                $this->errors[] = "Teacher ID {$id} not found";
                continue;
            }
            if ((int)$user->role_id !== self::ROLE_TEACHER) {
                $this->errors[] = "{$user->username} is not a teacher";
            }
        }

        // Check subjects exist
        foreach ($subjectIds as $id) {
            if (!Subject::find($id)) {
                $this->errors[] = "Subject ID {$id} not found";
            }
        }

        // Check department has classes
        $majors = $department->majors;
        if ($majors->isEmpty()) {
            $this->warnings[] = "Department has no majors associated";
        } else {
            $classes = ClassModel::whereIn('major_id', $majors->pluck('id'))->get();
            if ($classes->isEmpty() && (!empty($studentIds) || !empty($teacherIds) || !empty($subjectIds))) {
                $this->warnings[] = "Department has no classes - will create one automatically";
            }
        }

        return $this->isValid();
    }

    /**
     * Check for enrollment conflicts (e.g., teacher in multiple conflicting classes)
     * 
     * @param Department $department
     * @param array $teacherIds
     * @return Collection
     */
    public function checkConflicts(Department $department, array $teacherIds): Collection
    {
        $conflicts = collect();
        
        if (!$department->relationLoaded('majors')) {
            $department->load('majors');
        }
        
        $departmentClassIds = ClassModel::whereIn('major_id', $department->majors->pluck('id'))
            ->pluck('id')
            ->toArray();

        foreach ($teacherIds as $teacherId) {
            $teacher = User::find($teacherId);
            if (!$teacher) continue;

            // Get all classes this teacher is currently in
            $currentClasses = $teacher->teacherClasses()->pluck('class_models.id')->toArray();
            
            // Find common classes (would create duplicates)
            $commonClasses = array_intersect($currentClasses, $departmentClassIds);
            
            if (!empty($commonClasses)) {
                $classNames = ClassModel::whereIn('id', $commonClasses)->pluck('class_name')->toArray();
                $conflicts->push([
                    'teacher_id' => $teacherId,
                    'teacher_name' => $teacher->username,
                    'teacher_email' => $teacher->email ?? 'N/A',
                    'message' => "Teacher {$teacher->username} would be enrolled in conflicting classes",
                    'class_ids' => $commonClasses,
                    'class_names' => $classNames,
                    'conflict_count' => count($commonClasses),
                ]);
            }
        }

        return $conflicts;
    }

    /**
     * Validate batch enrollment with transactions
     * 
     * @param array $enrollments
     * @return array
     */
    public function validateBatchEnrollment(array $enrollments): array
    {
        $results = [];
        
        foreach ($enrollments as $key => $enrollment) {
            $type = $enrollment['type'] ?? 'student';
            $userId = $enrollment['user_id'] ?? null;
            $classIds = $enrollment['class_ids'] ?? [];
            
            if ($type === 'student') {
                $valid = $this->validateStudentEnrollment($userId, $classIds);
            } elseif ($type === 'teacher') {
                $valid = $this->validateTeacherEnrollment($userId, $classIds);
            } else {
                $results[$key] = [
                    'valid' => false,
                    'errors' => ['Invalid enrollment type: ' . $type],
                    'warnings' => [],
                ];
                continue;
            }
            
            $results[$key] = $this->getStatus();
        }
        
        return $results;
    }

    /**
     * Validate class capacity before enrollment
     * 
     * @param int $classId
     * @param int $newStudentsCount
     * @return bool
     */
    public function validateClassCapacity(int $classId, int $newStudentsCount = 1): bool
    {
        $this->resetValidation();
        
        $class = ClassModel::find($classId);
        if (!$class) {
            $this->errors[] = "Class ID {$classId} not found";
            return false;
        }
        
        $maxCapacity = $class->max_students ?? null;
        if ($maxCapacity) {
            $currentCount = $class->students()->count();
            if ($currentCount + $newStudentsCount > $maxCapacity) {
                $this->errors[] = "Cannot enroll {$newStudentsCount} new student(s). Class capacity is {$maxCapacity}, currently has {$currentCount} students.";
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check for schedule conflicts for a student
     * 
     * @param int $studentId
     * @param array $classIds
     * @return Collection
     */
    public function checkScheduleConflicts(int $studentId, array $classIds): Collection
    {
        $conflicts = collect();
        
        $student = User::find($studentId);
        if (!$student || (int)$student->role_id !== self::ROLE_STUDENT) {
            return $conflicts;
        }
        
        // Get student's current classes with schedules
        $currentClasses = $student->classes()->with('schedule')->get();
        
        // Get potential new classes with schedules
        $newClasses = ClassModel::whereIn('id', $classIds)->with('schedule')->get();
        
        foreach ($newClasses as $newClass) {
            foreach ($currentClasses as $currentClass) {
                if ($this->hasScheduleConflict($currentClass, $newClass)) {
                    $conflicts->push([
                        'student_id' => $studentId,
                        'student_name' => $student->username,
                        'existing_class' => $currentClass->class_name,
                        'new_class' => $newClass->class_name,
                        'message' => "Schedule conflict between {$currentClass->class_name} and {$newClass->class_name}",
                    ]);
                    break;
                }
            }
        }
        
        return $conflicts;
    }

    /**
     * Check if two classes have schedule conflicts
     * 
     * @param ClassModel $class1
     * @param ClassModel $class2
     * @return bool
     */
    protected function hasScheduleConflict(ClassModel $class1, ClassModel $class2): bool
    {
        // This is a simplified check - expand based on your schedule structure
        if (!$class1->schedule || !$class2->schedule) {
            return false;
        }
        
        return $class1->schedule->day_of_week === $class2->schedule->day_of_week &&
               $this->timeOverlaps($class1->schedule->start_time, $class1->schedule->end_time, 
                                   $class2->schedule->start_time, $class2->schedule->end_time);
    }

    /**
     * Check if two time ranges overlap
     * 
     * @param string $start1
     * @param string $end1
     * @param string $start2
     * @param string $end2
     * @return bool
     */
    protected function timeOverlaps(string $start1, string $end1, string $start2, string $end2): bool
    {
        return $start1 < $end2 && $start2 < $end1;
    }

    /**
     * Reset validation state
     */
    protected function resetValidation(): void
    {
        $this->errors = [];
        $this->warnings = [];
    }

    /**
     * Check if validation passed
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get all validation errors
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get all validation warnings
     * 
     * @return array
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    /**
     * Get validation status as array
     * 
     * @return array
     */
    public function getStatus(): array
    {
        return [
            'valid' => $this->isValid(),
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'error_count' => count($this->errors),
            'warning_count' => count($this->warnings),
            'summary' => $this->getValidationSummary(),
        ];
    }

    /**
     * Get human-readable validation summary
     * 
     * @return string
     */
    public function getValidationSummary(): string
    {
        if ($this->isValid()) {
            $warningText = !empty($this->warnings) ? ' with ' . count($this->warnings) . ' warning(s)' : '';
            return "Validation passed{$warningText}";
        }
        
        return "Validation failed with " . count($this->errors) . " error(s)";
    }

    /**
     * Log validation results
     * 
     * @param string $context
     * @return void
     */
    public function logValidationResults(string $context = 'enrollment'): void
    {
        if (!empty($this->errors)) {
            Log::warning("{$context} validation errors", [
                'errors' => $this->errors,
                'warnings' => $this->warnings,
            ]);
        }
        
        if (!empty($this->warnings)) {
            Log::info("{$context} validation warnings", [
                'warnings' => $this->warnings,
            ]);
        }
    }
}