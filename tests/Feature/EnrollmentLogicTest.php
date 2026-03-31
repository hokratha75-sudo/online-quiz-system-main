<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentLogicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        Role::updateOrCreate(['id' => 1], ['role_name' => 'Admin']);
        Role::updateOrCreate(['id' => 2], ['role_name' => 'Teacher']);
        Role::updateOrCreate(['id' => 3], ['role_name' => 'Student']);
    }

    public function test_can_assign_users_and_subjects_to_department()
    {
        // 1. Setup Data
        $admin = User::factory()->create(['role_id' => 1]);
        $department = Department::create([
            'department_name' => 'Computer Science',
            'code' => 'CS'
        ]);

        $teacher = User::factory()->create(['role_id' => 2, 'username' => 't1']);
        $student = User::factory()->create(['role_id' => 3, 'username' => 's1']);
        $subject = Subject::create([
            'subject_name' => 'Algorithms',
            'code' => 'CS101',
            'created_by' => $admin->id
        ]);

        // 2. Act: Perform Enrollment
        $response = $this->actingAs($admin)->put(route('admin.enrollments.update', $department->id), [
            'teachers' => [$teacher->id],
            'students' => [$student->id],
            'subjects' => [$subject->id]
        ]);

        // 3. Assert: Results saved correctly
        $response->assertRedirect(route('admin.enrollments.index'));
        
        $teacher->refresh();
        $student->refresh();
        $subject->refresh();

        $this->assertEquals($department->id, $teacher->department_id);
        $this->assertEquals($department->id, $student->department_id);
        $this->assertEquals($department->id, $subject->department_id);
    }

    public function test_can_sync_and_clear_assignments()
    {
        // 1. Setup Data with existing department
        $admin = User::factory()->create(['role_id' => 1]);
        $department = Department::create(['department_name' => 'CS', 'code' => 'CS']);

        $teacher = User::factory()->create(['role_id' => 2, 'department_id' => $department->id, 'username' => 't1']);
        $student = User::factory()->create(['role_id' => 3, 'department_id' => $department->id, 'username' => 's1']);
        $subject = Subject::create([
            'subject_name' => 'Algo', 
            'code' => 'CS101', 
            'department_id' => $department->id,
            'created_by' => $admin->id
        ]);

        // 2. Act: Sync with EMPTY arrays
        $response = $this->actingAs($admin)->put(route('admin.enrollments.update', $department->id), [
            'teachers' => [],
            'students' => [],
            'subjects' => []
        ]);

        // 3. Assert: Assignments cleared
        $teacher->refresh();
        $student->refresh();
        $subject->refresh();

        $this->assertNull($teacher->department_id);
        $this->assertNull($student->department_id);
        $this->assertNull($subject->department_id);
    }
}
