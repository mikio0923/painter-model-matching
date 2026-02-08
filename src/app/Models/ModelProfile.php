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
        'birthdate',
        'gender',
        'prefecture',
        'activity_regions',
            'height',
            'bust',
            'waist',
            'hip',
            'shoe_size',
        'clothing_size',
        'model_types',
        'body_type',
        'hair_type',
        'bio',
        'occupation',
        'hobbies',
        'avoid_work_types',
        'experience',
        'portfolio_url',
        'sns_links',
        'style_tags',
        'pose_ranges',
        'online_available',
        'reward_min',
        'reward_max',
        'is_public',
        'identity_verified',
        'terms_text',
    ];

    protected $casts = [
        'activity_regions' => 'array',
        'model_types' => 'array',
        'avoid_work_types' => 'array',
        'style_tags' => 'array',
        'pose_ranges' => 'array',
        'sns_links' => 'array',
        'online_available' => 'boolean',
        'is_public' => 'boolean',
        'identity_verified' => 'boolean',
        'birthdate' => 'date',
    ];

    // 年齢を自動計算（生年月日から）
    public function getAgeAttribute($value)
    {
        if ($this->birthdate) {
            return $this->birthdate->age;
        }
        return $value;
    }

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

    public function questions(): HasMany
    {
        return $this->hasMany(ModelProfileQuestion::class)->latest();
    }
}
