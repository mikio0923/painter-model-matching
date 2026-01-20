<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'model_id',
        'message',
        'status',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(User::class, 'model_id');
    }

    // ステータスラベル
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => '未対応',
            'accepted' => '承認',
            'rejected' => '却下',
            default => '不明',
        };
    }
}
