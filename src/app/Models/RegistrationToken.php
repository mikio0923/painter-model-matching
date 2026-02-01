<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrationToken extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'token',
        'role',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * トークンを生成して保存
     */
    public static function createToken(string $email, string $role): string
    {
        $token = Str::random(64);
        
        self::updateOrCreate(
            ['email' => $email],
            [
                'token' => hash('sha256', $token),
                'role' => $role,
                'created_at' => now(),
            ]
        );

        return $token;
    }

    /**
     * トークンを検証
     */
    public static function verifyToken(string $email, string $token): ?self
    {
        $record = self::where('email', $email)->first();
        
        if (!$record) {
            return null;
        }

        // トークンが24時間以内かチェック
        if ($record->created_at->addHours(24)->isPast()) {
            $record->delete();
            return null;
        }

        // トークンを検証
        if (!hash_equals($record->token, hash('sha256', $token))) {
            return null;
        }

        return $record;
    }
}
