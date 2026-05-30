<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentHistory extends Model
{
    protected $fillable = [
        'department_id',
        'class_model_id',
        'user_id',
        'subject_id',
        'admin_id',
        'action',
        'action_type',
        'reason',
        'old_value',
        'new_value',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Use 'classModel' (not 'class' — that's a PHP reserved word)
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_model_id');
    }

    // Alias so blade views using $record->class still work
    public function getClassAttribute()
    {
        return $this->classModel;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}