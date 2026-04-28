<?php

use Illuminate\Support\Facades\Route;
use App\Models\Quiz;
use App\Http\Controllers\PostController;

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

// ========================================
// POST CRUD API (Training / Demo Purpose)
// ========================================
// GET    /api/posts        → index   (list all)
// POST   /api/posts        → store   (create)
// GET    /api/posts/{id}   → show    (view one)
// PUT    /api/posts/{id}   → update  (edit)
// DELETE /api/posts/{id}   → destroy (delete)
Route::apiResource('posts', PostController::class);
