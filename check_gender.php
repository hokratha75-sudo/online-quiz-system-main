<?php
require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$stats = Illuminate\Support\Facades\DB::table('users')
    ->where('role_id', 3)
    ->select('sex', Illuminate\Support\Facades\DB::raw('count(*) as count'))
    ->groupBy('sex')
    ->get();

echo "Gender Stats in DB:\n";
foreach($stats as $s) {
    echo "- '{$s->sex}': {$s->count}\n";
}
