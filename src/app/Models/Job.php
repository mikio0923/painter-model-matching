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
        'category',
        'reward_amount',
        'reward_unit',
        'transportation_fee',
        'costume_provided',
        'target',
        'recruitment_number',
        'location_type',
        'prefecture',
        'city',
        'address',
        'access',
        'scheduled_date',
        'apply_deadline',
        'deadline_reminder_sent_at',
        'status',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'apply_deadline' => 'date',
        'deadline_reminder_sent_at' => 'datetime',
    ];

    public function painter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'painter_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }

    public function pendingOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class)->where('status', JobOffer::STATUS_PENDING);
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
            'done' => '完了',
            default => '不明',
        };
    }
}

