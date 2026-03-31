<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'remember_token',
    ];

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
        return $this->belongsToMany(ClassModel::class, 'class_user')->withPivot('role')->withTimestamps();
    }

    public function taughtSubjects()
    {
        return $this->belongsToMany(ClassModel::class, 'class_user')->wherePivot('role', 'teacher');
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
}
