<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectMaterial extends Model
{
    protected $fillable = ['subject_id', 'title', 'description', 'type', 'content', 'created_by'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIconAttribute()
    {
        return match($this->type) {
            'file' => 'fa-file-alt',
            'video' => 'fa-play-circle',
            'link' => 'fa-link',
            default => 'fa-book-open',
        };
    }
}
