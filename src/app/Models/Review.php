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

    protected $casts = [
        'rating' => 'integer',
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

    /**
     * 1〜5 の整数評価。レガシー互換のため getRatingLabelAttribute も残す。
     */
    public function getRatingLabelAttribute(): string
    {
        $r = (int) $this->rating;
        return $r >= 1 && $r <= 5 ? "★ {$r} / 5" : '未評価';
    }
}
