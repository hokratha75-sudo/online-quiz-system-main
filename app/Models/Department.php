<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_name',
        'code',
        'description',
    ];

    public function getNameAttribute(): string
    {
        return (string) $this->department_name;
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['department_name'] = $value;
    }

    public function majors()
    {
        return $this->hasMany(Major::class);
    }

    public function classes()
    {
        return $this->hasManyThrough(ClassModel::class, Major::class, 'department_id', 'major_id');
    }

    /**
     * Get the subjects for the department.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the users for the department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
