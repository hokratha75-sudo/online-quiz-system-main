<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Clear existing data to avoid conflicts (removed to avoid cascading truncation of users)

        // 2. Seed Departments
        $depts = [
            ['department_name' => 'Computer Science'],
            ['department_name' => 'Economic Business and Tourism'],
            ['department_name' => 'English'],
            ['department_name' => 'Mathematics'],
            ['department_name' => 'Physics'],
        ];

        foreach ($depts as $dept) {
            DB::table('departments')->insert(array_merge($dept, [
                'created_at' => now(),
            ]));
        }

        // 3. Seed Subjects
        $csId = DB::table('departments')->where('department_name', 'Computer Science')->first()->id;
        $ebtId = DB::table('departments')->where('department_name', 'Economic Business and Tourism')->first()->id;
        
        // Find the admin user
        $admin = DB::table('users')->where('username', 'admin')->first();
        if (!$admin) {
             throw new \Exception("Admin user not found. Please run UsersTableSeeder first.");
        }
        $adminId = $admin->id; 

        $subjects = [
            ['subject_name' => 'HTML & CSS', 'department_id' => $csId, 'created_by' => $adminId],
            ['subject_name' => 'Data Science', 'department_id' => $csId, 'created_by' => $adminId],
            ['subject_name' => 'Business Management', 'department_id' => $ebtId, 'created_by' => $adminId],
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert(array_merge($subject, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
