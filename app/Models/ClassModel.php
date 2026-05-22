<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class ClassModel extends Model
{
    use SoftDeletes;
    protected $fillable = ['code', 'name', 'major_id', 'academic_year'];
    public function subjects()
{
    return $this->belongsToMany(
            Subject::class,
            'class_subject',
            'class_model_id',
            'subject_id'
        );
    }
    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    // public function subjects()
    // {
    //     return $this->belongsToMany(Subject::class, 'class_subject')->withTimestamps();
    // }

    public function users()
    {
        // avoid selecting pivot 'role' column directly because some DB states lack this column
        return $this->belongsToMany(User::class, 'class_user')->withTimestamps();
    }

    public function students()
    {
        // only filter by pivot 'role' if the column exists in the database
        if (Schema::hasColumn('class_user', 'role')) {
            return $this->belongsToMany(User::class, 'class_user')->wherePivot('role', '=', 'student')->withTimestamps();
        }

        return $this->belongsToMany(User::class, 'class_user')->withTimestamps();
    }
}
