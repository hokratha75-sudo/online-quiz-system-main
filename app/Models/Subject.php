<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;
    protected $fillable = ['subject_name', 'department_id', 'major_id', 'created_by', 'code', 'credits'];

    public function getNameAttribute(): string
    {
        return (string) $this->subject_name;
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['subject_name'] = $value;
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_subject')->withTimestamps();
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(SubjectMaterial::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
