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
        'payment_received_at',
    ];

    protected $casts = [
        'payment_received_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * 取引完了済（モデルが報酬受領を確認済）かどうか
     */
    public function isCompleted(): bool
    {
        return $this->payment_received_at !== null;
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
