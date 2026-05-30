<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use SoftDeletes;

    protected $table = 'class_models';

    protected $fillable = [
        'code',
        'name',           // ← actual DB column (was 'class_name' — wrong)
        'major_id',
        'academic_year',
        'show_in_enrollments',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_model_id', 'subject_id')
                    ->withTimestamps();
    }

    // All users regardless of role
    public function users()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_model_id', 'user_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // Students only
    public function students()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_model_id', 'user_id')
                    ->wherePivot('role', 'student')
                    ->withTimestamps();
    }

    // Teachers only
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_model_id', 'user_id')
                    ->wherePivot('role', 'teacher')
                    ->withTimestamps();
    }

    // ── Accessor: $class->class_name still works in old blade views ───────
    public function getClassNameAttribute(): string
    {
        return $this->attributes['name'] ?? '';
    }
}