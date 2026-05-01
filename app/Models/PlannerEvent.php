<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlannerEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'start',
        'end',
        'background_color',
        'border_color',
        'is_all_day',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'is_all_day' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
