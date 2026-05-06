<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailPreference extends Model
{
    protected $fillable = [
        'user_id',
        'application_emails',
        'message_emails',
        'review_emails',
        'reminder_emails',
        'marketing_emails',
    ];

    protected $casts = [
        'application_emails' => 'boolean',
        'message_emails'     => 'boolean',
        'review_emails'      => 'boolean',
        'reminder_emails'    => 'boolean',
        'marketing_emails'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 指定カテゴリでメール送信を許可しているか
     * レコードが無いユーザーはデフォルト全許可とみなす
     */
    public static function allows(?User $user, string $category): bool
    {
        if (!$user) {
            return false;
        }

        $pref = $user->emailPreference;
        if (!$pref) {
            return true;
        }

        return match($category) {
            'application' => $pref->application_emails,
            'message'     => $pref->message_emails,
            'review'      => $pref->review_emails,
            'reminder'    => $pref->reminder_emails,
            'marketing'   => $pref->marketing_emails,
            default       => true,
        };
    }
}
