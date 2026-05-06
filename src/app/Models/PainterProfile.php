<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PainterProfile extends Model
{
    protected $fillable = [
        'user_id',
        'display_name',
        'profile_image_path',
        'gender',
        'bio',
        'experience',
        'years_active',
        'accepts_offers',
        'art_styles',
        'specialties',
        'portfolio_url',
        'sns_links',
        'prefecture',
        'activity_regions',
    ];

    protected $casts = [
        'art_styles'       => 'array',
        'specialties'      => 'array',
        'sns_links'        => 'array',
        'activity_regions' => 'array',
        'accepts_offers'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
