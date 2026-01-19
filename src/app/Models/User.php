<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 画家プロフィール（1:1）
    public function painterProfile(): HasOne
    {
        return $this->hasOne(PainterProfile::class);
    }

    // モデルプロフィール（1:1）
    public function modelProfile(): HasOne
    {
        return $this->hasOne(ModelProfile::class);
    }

    // 画家が投稿した依頼（1:N）
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'painter_id');
    }

    // モデルが応募した応募一覧（1:N）
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'model_id');
    }

    // 送信メッセージ（1:N）
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // 受信メッセージ（1:N）
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // 書いたレビュー（1:N）
    public function writtenReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    // 受け取ったレビュー（1:N）
    public function receivedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    // 便利メソッド（任意）
    public function isPainter(): bool
    {
        return $this->role === 'painter';
    }

    public function isModel(): bool
    {
        return $this->role === 'model';
    }
}
