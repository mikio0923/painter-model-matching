<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PainterProfile extends Model
{
    protected $fillable = [
        'user_id',
        'display_name',
        'art_styles',
        'portfolio_url',
        'prefecture',
    ];

    protected $casts = [
        'art_styles' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
