<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'reviewer_id',
        'reviewed_user_id',
        'rating',
        'comment',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_user_id');
    }

    // 評価の表示用
    public function getRatingLabelAttribute(): string
    {
        return match($this->rating) {
            'very_good' => '非常に良い・良い',
            'good' => '良い',
            'bad' => '悪い',
            default => '不明',
        };
    }
}
