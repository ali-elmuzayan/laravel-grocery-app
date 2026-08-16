<?php

namespace App\Models;

use App\Domain\Auth\Enums\OtpType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'code', 'type', 'expires_at', 'verified_at', 'attempts'])]
class UserOtp extends Model
{
    public const MAX_ATTEMPTS = 5;

    public const EXPIRY_MINUTES = 5;


    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'type' => OtpType::class,
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    /** 
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helpers Methods
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function hasExceededAttempts(): bool
    {
        return $this->attempts >= self::MAX_ATTEMPTS;
    }


}
