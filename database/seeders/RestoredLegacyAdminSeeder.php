<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RestoredLegacyAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $adminId = $this->upsertUser(
            username: 'admin',
            email: 'admin@gmail.com',
            roleId: 1,
            password: 'admin123'
        );

        $teacher2Id = $this->upsertUser(
            username: 'teacher2',
            email: 'teacher2@example.com',
            roleId: 2,
            password: 'teacher123'
        );

        $teacher3Id = $this->upsertUser(
            username: 'teacher3',
            email: 'teacher3@example.com',
            roleId: 2,
            password: 'teacher123'
        );

        $teacher4Id = $this->upsertUser(
            username: 'teacher4',
            email: 'teacher4@example.com',
            roleId: 2,
            password: 'teacher123'
        );

        foreach (range(5, 8) as $index) {
            $this->upsertUser(
                username: 'teacher' . $index,
                email: 'teacher' . $index . '@example.com',
                roleId: 2,
                password: 'teacher123'
            );
        }

        DB::table('users')->where('username', 'teacher1')->update([
            'status' => 'inactive',
            'updated_at' => $now,
        ]);

        $studentIds = [];
        $studentIds[] = $this->upsertUser(
            username: 'student1',
            email: 'student1@gmail.com',
            roleId: 3,
            password: 'student123'
        );

        foreach (range(2, 17) as $index) {
            $studentIds[] = $this->upsertUser(
                username: 'student' . $index,
                email: 'student' . $index . '@example.com',
                roleId: 3,
                password: 'student123'
            );
        }

        $scienceDeptId = $this->upsertDepartment(
            code: 'S&T002',
            name: 'Science and Technology',
            description: 'Legacy department restored from the original academic structure.'
        );

        $economicsDeptId = $this->upsertDepartment(
            code: 'EBT001',
            name: 'Economics Business and Technology',
            description: 'Legacy department restored from the original academic structure.'
        );

        $itMajorId = $this->upsertMajor(
            code: 'IT001',
            name: 'Information and Technology',
            departmentId: $scienceDeptId,
            description: 'Legacy academic major used in the original department pages.'
        );

        $this->upsertClass(
            code: 'G10',
            name: 'Grade 10',
            majorId: $itMajorId,
            academicYear: '2025/2026',
            showInEnrollments: false
        );

        $this->upsertClass(
            code: 'SR-001',
            name: 'Siem Reap',
            majorId: $itMajorId,
            academicYear: '2025/2026',
            showInEnrollments: false
        );

        $dataStructureId = $this->upsertSubject(
            code: 'SUB-001',
            name: 'Data Structure',
            departmentId: $scienceDeptId,
            majorId: $itMajorId,
            createdBy: $adminId,
            credits: 3
        );

        $mathematicsId = $this->upsertSubject(
            code: 'SUB-002',
            name: 'Mathematics',
            departmentId: $scienceDeptId,
            majorId: $itMajorId,
            createdBy: $adminId,
            credits: 3
        );

        DB::table('quizzes')->updateOrInsert(
            ['title' => 'Mathematics Placement Quiz', 'subject_id' => $mathematicsId],
            [
                'description' => 'Legacy sample quiz for the restored subject list.',
                'created_by' => $adminId,
                'status' => 'draft',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $computerScienceDeptId = $this->firstOrCreateDepartmentByName('Computer Science');
        $businessDeptId = $this->firstOrCreateDepartmentByName('Economic Business and Tourism');

        $webMajorId = $this->upsertMajor(
            code: 'WEB001',
            name: 'Web Development',
            departmentId: $computerScienceDeptId,
            description: 'Enrollment dashboard major.'
        );

        $dataMajorId = $this->upsertMajor(
            code: 'DATA001',
            name: 'Data Science',
            departmentId: $computerScienceDeptId,
            description: 'Enrollment dashboard major.'
        );

        $businessMajorId = $this->upsertMajor(
            code: 'BUS001',
            name: 'Business Management',
            departmentId: $businessDeptId,
            description: 'Enrollment dashboard major.'
        );

        $webSubjectId = $this->upsertSubject(
            code: 'WEB-SUB1',
            name: 'Website Fundamentals',
            departmentId: $computerScienceDeptId,
            majorId: $webMajorId,
            createdBy: $adminId,
            credits: 3
        );

        $analyticsSubjectId = $this->upsertSubject(
            code: 'DATA-SUB1',
            name: 'Analytics Basics',
            departmentId: $computerScienceDeptId,
            majorId: $dataMajorId,
            createdBy: $adminId,
            credits: 3
        );

        $businessSubjectId = $this->upsertSubject(
            code: 'BUS-SUB1',
            name: 'Business Communication',
            departmentId: $businessDeptId,
            majorId: $businessMajorId,
            createdBy: $adminId,
            credits: 3
        );

        $webYear1ClassId = $this->upsertClass(
            code: 'WEB-Y1A',
            name: 'Year 1 - Web A',
            majorId: $webMajorId,
            academicYear: '2025/2026',
            showInEnrollments: true
        );

        $dataYear1ClassId = $this->upsertClass(
            code: 'DATA-Y1A',
            name: 'Year 1 - Data A',
            majorId: $dataMajorId,
            academicYear: '2025/2026',
            showInEnrollments: true
        );

        $webYear2ClassId = $this->upsertClass(
            code: 'WEB-Y2A',
            name: 'Year 2 - Web A',
            majorId: $webMajorId,
            academicYear: '2025/2026',
            showInEnrollments: true
        );

        $businessYear1ClassId = $this->upsertClass(
            code: 'BUS-Y1A',
            name: 'Year 1 - Business A',
            majorId: $businessMajorId,
            academicYear: '2025/2026',
            showInEnrollments: true
        );

        $businessYear2ClassId = $this->upsertClass(
            code: 'BUS-Y2A',
            name: 'Year 2 - Business A',
            majorId: $businessMajorId,
            academicYear: '2025/2026',
            showInEnrollments: true
        );

        $this->syncClassSubjects($webYear1ClassId, [$webSubjectId]);
        $this->syncClassSubjects($dataYear1ClassId, [$analyticsSubjectId]);
        $this->syncClassSubjects($webYear2ClassId, []);
        $this->syncClassSubjects($businessYear1ClassId, [$businessSubjectId]);
        $this->syncClassSubjects($businessYear2ClassId, []);

        $this->assignClassMembers($webYear1ClassId, [$teacher2Id], array_slice($studentIds, 0, 11));
        $this->assignClassMembers($dataYear1ClassId, [$teacher3Id], array_slice($studentIds, 11, 3));
        $this->assignClassMembers($webYear2ClassId, [], []);
        $this->assignClassMembers($businessYear1ClassId, [$teacher4Id], array_slice($studentIds, 14, 3));
        $this->assignClassMembers($businessYear2ClassId, [], []);

        DB::table('users')->where('id', $teacher2Id)->update([
            'department_id' => $computerScienceDeptId,
            'major_id' => $webMajorId,
            'updated_at' => $now,
        ]);

        DB::table('users')->where('id', $teacher3Id)->update([
            'department_id' => $computerScienceDeptId,
            'major_id' => $dataMajorId,
            'updated_at' => $now,
        ]);

        DB::table('users')->where('id', $teacher4Id)->update([
            'department_id' => $businessDeptId,
            'major_id' => $businessMajorId,
            'updated_at' => $now,
        ]);

        foreach (array_slice($studentIds, 0, 11) as $studentId) {
            DB::table('users')->where('id', $studentId)->update([
                'department_id' => $computerScienceDeptId,
                'major_id' => $webMajorId,
                'updated_at' => $now,
            ]);
        }

        foreach (array_slice($studentIds, 11, 3) as $studentId) {
            DB::table('users')->where('id', $studentId)->update([
                'department_id' => $computerScienceDeptId,
                'major_id' => $dataMajorId,
                'updated_at' => $now,
            ]);
        }

        foreach (array_slice($studentIds, 14, 3) as $studentId) {
            DB::table('users')->where('id', $studentId)->update([
                'department_id' => $businessDeptId,
                'major_id' => $businessMajorId,
                'updated_at' => $now,
            ]);
        }
    }

    private function upsertUser(string $username, string $email, int $roleId, string $password): int
    {
        $existing = DB::table('users')->where('username', $username)->first();
        $now = now();

        if ($existing) {
            DB::table('users')->where('id', $existing->id)->update([
                'email' => $email,
                'role_id' => $roleId,
                'status' => 'active',
                'updated_at' => $now,
            ]);

            return (int) $existing->id;
        }

        return (int) DB::table('users')->insertGetId([
            'username' => $username,
            'email' => $email,
            'password_hash' => Hash::make($password),
            'role_id' => $roleId,
            'status' => 'active',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function upsertDepartment(string $code, string $name, ?string $description = null): int
    {
        $existing = DB::table('departments')->where('code', $code)->first();
        $now = now();

        if ($existing) {
            DB::table('departments')->where('id', $existing->id)->update([
                'department_name' => $name,
                'description' => $description,
                'updated_at' => $now,
            ]);

            return (int) $existing->id;
        }

        return (int) DB::table('departments')->insertGetId([
            'code' => $code,
            'department_name' => $name,
            'description' => $description,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function firstOrCreateDepartmentByName(string $name): int
    {
        $existingId = DB::table('departments')
            ->where('department_name', $name)
            ->orderBy('id')
            ->value('id');

        if ($existingId) {
            return (int) $existingId;
        }

        return (int) DB::table('departments')->insertGetId([
            'department_name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function upsertMajor(string $code, string $name, int $departmentId, ?string $description = null): int
    {
        $existing = DB::table('majors')->where('code', $code)->first();
        $now = now();

        if ($existing) {
            DB::table('majors')->where('id', $existing->id)->update([
                'department_id' => $departmentId,
                'name' => $name,
                'description' => $description,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);

            return (int) $existing->id;
        }

        return (int) DB::table('majors')->insertGetId([
            'department_id' => $departmentId,
            'code' => $code,
            'name' => $name,
            'description' => $description,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function upsertClass(
        string $code,
        string $name,
        int $majorId,
        ?string $academicYear = null,
        bool $showInEnrollments = false
    ): int {
        $existing = DB::table('class_models')->where('code', $code)->first();
        $now = now();

        if ($existing) {
            DB::table('class_models')->where('id', $existing->id)->update([
                'name' => $name,
                'major_id' => $majorId,
                'academic_year' => $academicYear,
                'show_in_enrollments' => $showInEnrollments,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);

            return (int) $existing->id;
        }

        return (int) DB::table('class_models')->insertGetId([
            'code' => $code,
            'name' => $name,
            'major_id' => $majorId,
            'academic_year' => $academicYear,
            'show_in_enrollments' => $showInEnrollments,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function upsertSubject(
        string $code,
        string $name,
        int $departmentId,
        int $majorId,
        int $createdBy,
        int $credits = 3
    ): int {
        $existing = DB::table('subjects')->where('code', $code)->first();
        $now = now();

        if ($existing) {
            DB::table('subjects')->where('id', $existing->id)->update([
                'subject_name' => $name,
                'department_id' => $departmentId,
                'major_id' => $majorId,
                'credits' => $credits,
                'created_by' => $createdBy,
                'updated_at' => $now,
                'deleted_at' => null,
            ]);

            return (int) $existing->id;
        }

        return (int) DB::table('subjects')->insertGetId([
            'code' => $code,
            'subject_name' => $name,
            'department_id' => $departmentId,
            'major_id' => $majorId,
            'credits' => $credits,
            'created_by' => $createdBy,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function syncClassSubjects(int $classId, array $subjectIds): void
    {
        DB::table('class_subject')->where('class_model_id', $classId)->delete();

        foreach ($subjectIds as $subjectId) {
            DB::table('class_subject')->insert([
                'class_model_id' => $classId,
                'subject_id' => $subjectId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function assignClassMembers(int $classId, array $teacherIds, array $studentIds): void
    {
        DB::table('class_user')->where('class_model_id', $classId)->delete();

        foreach ($teacherIds as $teacherId) {
            DB::table('class_user')->insert([
                'class_model_id' => $classId,
                'user_id' => $teacherId,
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($studentIds as $studentId) {
            DB::table('class_user')->insert([
                'class_model_id' => $classId,
                'user_id' => $studentId,
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
