<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// បង្គាប់ឱ្យប្រព័ន្ធ Backup Database ដោយស្វ័យប្រវត្តិជារៀងរាល់យប់ម៉ោង 1 អាធ្រាត្រ
\Illuminate\Support\Facades\Schedule::command('backup:clean')->daily()->at('01:00');
\Illuminate\Support\Facades\Schedule::command('backup:run --only-db')->daily()->at('01:30');
