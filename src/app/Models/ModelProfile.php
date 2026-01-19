<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ModelProfile extends Model
{
    protected $fillable = [
        'user_id',
        'display_name',
        'profile_image_path',
        'age',
        'gender',
        'prefecture',
        'height',
        'body_type',
        'hair_type',
        'bio',
        'experience',
        'portfolio_url',
        'sns_links',
        'style_tags',
        'pose_ranges',
        'online_available',
        'reward_min',
        'reward_max',
        'is_public',
    ];

    protected $casts = [
        'style_tags' => 'array',
        'pose_ranges' => 'array',
        'sns_links' => 'array',
        'online_available' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ModelProfileImage::class)->orderBy('display_order');
    }

    public function mainImage(): ?ModelProfileImage
    {
        return $this->images()->where('is_main', true)->first()
            ?? $this->images()->orderBy('display_order')->first();
    }

    // お気に入り（ポリモーフィック）
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
}
