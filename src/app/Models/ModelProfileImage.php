<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelProfileImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_profile_id',
        'image_path',
        'display_order',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function modelProfile(): BelongsTo
    {
        return $this->belongsTo(ModelProfile::class);
    }
}
