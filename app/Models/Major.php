<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Major extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'department_id',
        'description',
    ];

    /**
     * Get the department that owns the major.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the classes for the major.
     */
    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'major_id');
    }

    /**
     * Get the subjects for the major.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'major_id');
    }
}
