<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username', 
        'first_name',
        'last_name',
        'email', 
        'phone',
        'address',
        'birthday',
        'sex',
        'auth_method',
        'password_hash', 
        'role_id', 
        'department_id', 
        'major_id',
        'profile_photo', 
        'status',
        'is_suspended',
        'force_password_change'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Disable remember token as it doesn't exist in the database schema.
     */
    public function getRememberTokenName()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // Do nothing
    }

    public function getRememberToken()
    {
        return null;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function classes()
    {
        // avoid selecting pivot 'role' column directly because some DB states lack this column
        return $this->belongsToMany(ClassModel::class, 'class_user')->withTimestamps();
    }

    public function taughtSubjects()
    {
        if (Schema::hasColumn('class_user', 'role')) {
            return $this->belongsToMany(ClassModel::class, 'class_user')->wherePivot('role', '=', 'teacher');
        }

        return $this->belongsToMany(ClassModel::class, 'class_user');
    }

    public function isAdmin(): bool
    {
        return (int)$this->role_id === 1;
    }

    public function isTeacher(): bool
    {
        return (int)$this->role_id === 2;
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }
}
