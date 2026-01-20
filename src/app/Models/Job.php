<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Job extends Model
{
    protected $table = 'painter_jobs';

    protected $fillable = [
        'painter_id',
        'title',
        'description',
        'usage_purpose',
        'reward_amount',
        'reward_unit',
        'location_type',
        'prefecture',
        'city',
        'scheduled_date',
        'apply_deadline',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'apply_deadline' => 'date',
    ];

    public function painter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'painter_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // よく使うscope（任意）
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    // お気に入り（ポリモーフィック）
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    // ステータスラベル
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open' => '公開中',
            'closed' => '締切',
            'completed' => '完了',
            default => '不明',
        };
    }
}

