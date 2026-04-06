<?php

use Illuminate\Support\Facades\Route;
use App\Models\Quiz;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// បើកឱ្យចូលមើលបញ្ជី Quiz ដោយមិនបាច់ Login (សម្រាប់តេស្ត)
Route::get('/quizzes', function () {
    return Quiz::select('id', 'title', 'description')->get();
});

// Route តេស្តសាមញ្ញបំផុត
Route::get('/test', function () {
    return response()->json(['message' => 'API is working! Hello from Quiz System.']);
});
