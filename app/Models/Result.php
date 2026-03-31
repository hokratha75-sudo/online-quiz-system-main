<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_id',
        'score',
        'started_at',
        'completed_at',
        'passed',
        'is_published',
        'manual_score',
        'teacher_feedback',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'passed' => 'boolean',
        'is_published' => 'boolean',
        'manual_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }
}
