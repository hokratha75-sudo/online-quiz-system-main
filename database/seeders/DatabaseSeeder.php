<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            AcademicStructureSeeder::class,
            SubjectsTableSeeder::class,
            QuizzesTableSeeder::class,
            QuestionsTableSeeder::class,
            AnswersTableSeeder::class,
            ResultsTableSeeder::class,
            RestoredLegacyAdminSeeder::class,
        ]);
    }
}
