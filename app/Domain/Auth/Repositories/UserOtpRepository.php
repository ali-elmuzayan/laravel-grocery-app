<?php

namespace App\Domain\Auth\Repositories;

use App\Domain\Auth\Contracts\UserOtpRepositoryInterface;
use App\Domain\Auth\Enums\OtpType;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Carbon;

class UserOtpRepository implements UserOtpRepositoryInterface
{
    public function create(User $user, OtpType $type, string $hashedCode): UserOtp
    {
        return UserOtp::query()->create([
            'user_id' => $user->id,
            'code' => $hashedCode,
            'type' => $type,
            'expires_at' => now()->addMinutes(UserOtp::EXPIRY_MINUTES),
        ]);
    }

    public function incrementAttempts(UserOtp $otp): void
    {
        $otp->increment('attempts');
    }

    public function markVerified(UserOtp $otp): void
    {
        $otp->update(['verified_at' => Carbon::now()]);
    }
}
