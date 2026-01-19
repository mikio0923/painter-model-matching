<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    protected $fillable = [
        'user_id',
        'favoritable_id',
        'favoritable_type',
    ];

    /**
     * お気に入りを登録したユーザー
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * お気に入り対象（ポリモーフィック）
     */
    public function favoritable(): MorphTo
    {
        return $this->morphTo();
    }
}
