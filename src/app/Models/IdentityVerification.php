<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentityVerification extends Model
{
    protected $fillable = [
        'user_id',
        'document_type',
        'front_image_path',
        'back_image_path',
        'selfie_image_path',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'expires_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'expires_at' => 'date',
    ];

    public const STATUSES = ['pending', 'reviewing', 'approved', 'rejected'];

    public const DOCUMENT_TYPES = [
        'drivers_license' => '運転免許証',
        'my_number' => 'マイナンバーカード（表面）',
        'passport' => 'パスポート',
        'insurance_card' => '健康保険証',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => '審査待ち',
            'reviewing' => '審査中',
            'approved' => '承認済み',
            'rejected' => '差し戻し',
            default => '不明',
        };
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type;
    }
}
