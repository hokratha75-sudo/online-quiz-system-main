<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use SoftDeletes;
    protected $fillable = ['code', 'name', 'major_id', 'academic_year'];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject')->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'class_user')->withPivot('role')->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_user')->wherePivot('role', 'student')->withTimestamps();
    }
}
