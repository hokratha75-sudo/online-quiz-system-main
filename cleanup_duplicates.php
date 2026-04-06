<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = [
    'departments' => ['department_name'],
    'majors' => ['name', 'department_id'],
    'class_models' => ['name', 'major_id'],
    'subjects' => ['subject_name', 'department_id', 'major_id'],
    'quizzes' => ['title', 'subject_id'],
    'questions' => ['question_text', 'quiz_id'],
    'answers' => ['answer_text', 'question_id'],
    'users' => ['username']
];

DB::beginTransaction();
try {
    foreach ($tables as $table => $uniqueColumns) {
        echo "Checking table: $table\n";
        if (!Schema::hasTable($table)) {
            echo "  Table does not exist. Skipping.\n";
            continue;
        }

        $duplicates = DB::table($table)
            ->select($uniqueColumns)
            ->groupBy($uniqueColumns)
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isEmpty()) {
            echo "  No duplicates found.\n";
            continue;
        }

        $deletedCount = 0;
        foreach ($duplicates as $duplicate) {
            $query = DB::table($table);
            foreach ($uniqueColumns as $col) {
                $query->where($col, $duplicate->$col);
            }
            
            $ids = $query->orderBy('id')->pluck('id')->toArray();
            $keepId = array_shift($ids); // Keep the first one
            
            if (!empty($ids)) {
                $deletedCount += DB::table($table)->whereIn('id', $ids)->delete();
                echo "  Deleted " . count($ids) . " duplicates for " . json_encode($duplicate) . ", kept ID: $keepId\n";
            }
        }
        echo "  Total deleted from $table: $deletedCount\n\n";
    }
    
    // Handle User Email duplicates separately
    echo "Checking users for duplicate emails...\n";
    $dupEmails = DB::table('users')->whereNotNull('email')->select('email')->groupBy('email')->havingRaw('COUNT(*) > 1')->get();
    $emailDeleted = 0;
    foreach ($dupEmails as $dup) {
        $ids = DB::table('users')->where('email', $dup->email)->orderBy('id')->pluck('id')->toArray();
        array_shift($ids);
        $emailDeleted += DB::table('users')->whereIn('id', $ids)->delete();
    }
    echo "  Total users deleted by email: $emailDeleted\n\n";

    DB::commit();
    echo "Cleanup completed successfully!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
