<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    protected $fillable = [
        'job_id',
        'sender_id',
        'receiver_id',
        'body',
        'image_path',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
