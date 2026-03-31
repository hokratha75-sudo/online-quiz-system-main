<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Major;
use Illuminate\Http\Request;

class ClassModelController extends Controller
{
    public function index(Request $request)
    {
        $request->merge(['tab' => 'classes']);

        return app(MajorController::class)->index($request);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:class_models,name',
            'code' => 'required|string|max:50|unique:class_models',
            'major_id' => 'required|exists:majors,id',
            'academic_year' => 'nullable|string',
        ]);
        ClassModel::create($validated);

        return redirect()->route('admin.majors.index', ['tab' => 'classes'])
            ->with('success', 'Class added successfully.');
    }

    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:class_models,name,' . $class->id,
            'code' => 'required|string|max:50|unique:class_models,code,' . $class->id,
            'major_id' => 'required|exists:majors,id',
            'academic_year' => 'nullable|string',
        ]);
        $class->update($validated);

        return redirect()->route('admin.majors.index', ['tab' => 'classes'])
            ->with('success', 'Class updated successfully.');
    }

    public function bulkDelete(\Illuminate\Http\Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        \App\Models\ClassModel::whereIn('id', $request->ids)->delete();
        return redirect()->back()->with('success', 'Selected items deleted successfully.');
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();

        return redirect()->route('admin.majors.index', ['tab' => 'classes'])
            ->with('success', 'Class deleted successfully.');
    }

    public function restore($id)
    {
        $class = ClassModel::withTrashed()->findOrFail($id);
        $class->restore();

        return redirect()->route('admin.majors.index', ['tab' => 'classes'])
            ->with('success', 'Class restored successfully.');
    }

    public function show(ClassModel $class)
    {
        $class->load([
            'major.department',
            'students',
            'subjects' => function ($q) {
                $q->withCount('quizzes')->with('quizzes');
            },
        ]);

        $dashboardTitle = 'Class Detail';
        $userRole = 'admin';

        return view('admin.classes.show', compact('class', 'dashboardTitle', 'userRole'));
    }
}
