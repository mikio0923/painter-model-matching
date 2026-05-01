<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number_part1',
        'phone_number_part2',
        'phone_number_part3',
        'postal_code_part1',
        'postal_code_part2',
        'prefecture',
        'city',
        'street_number',
        'building_name',
        'deletion_requested_at',
        'anonymized_at',
        'deletion_reason',
        'deletion_feedback',
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
            'deletion_requested_at' => 'datetime',
            'anonymized_at' => 'datetime',
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

    // 通知（1:N）
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // 未読通知
    public function unreadNotifications(): HasMany
    {
        return $this->notifications()->whereNull('read_at');
    }

    // お気に入り（1:N）
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // 本人確認申請（1:N）
    public function identityVerifications(): HasMany
    {
        return $this->hasMany(IdentityVerification::class);
    }

    // 最新の本人確認申請
    public function latestIdentityVerification(): ?IdentityVerification
    {
        return $this->identityVerifications()->latest()->first();
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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
