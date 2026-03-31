<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,link,video,document',
            'content' => 'required|string',
            'description' => 'nullable|string',
        ]);

        SubjectMaterial::create([
            'subject_id' => $subject->id,
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Material added successfully!');
    }

    public function destroy(SubjectMaterial $material)
    {
        if ($material->created_by !== Auth::id() && Auth::user()->role_id !== 1) {
            abort(403);
        }

        $material->delete();
        return back()->with('success', 'Material deleted successfully!');
    }
}
