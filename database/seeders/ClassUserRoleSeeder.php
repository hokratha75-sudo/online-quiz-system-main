<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ClassUserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update role for existing class_user records based on user's role_id
        $classUsers = DB::table('class_user')->get();
        
        foreach ($classUsers as $classUser) {
            $user = User::find($classUser->user_id);
            
            if ($user) {
                $role = match($user->role_id) {
                    2 => 'teacher',
                    3 => 'student',
                    default => null
                };
                
                if ($role) {
                    DB::table('class_user')
                        ->where('class_model_id', $classUser->class_model_id)
                        ->where('user_id', $classUser->user_id)
                        ->update(['role' => $role]);
                }
            }
        }
    }
}