<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelProfileQuestion extends Model
{
    protected $fillable = [
        'model_profile_id',
        'asker_id',
        'question',
        'answer',
    ];

    public function modelProfile(): BelongsTo
    {
        return $this->belongsTo(ModelProfile::class);
    }

    public function asker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asker_id');
    }
}
