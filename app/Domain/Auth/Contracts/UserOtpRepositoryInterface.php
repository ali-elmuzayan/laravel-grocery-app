<?php

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\Enums\OtpType;
use App\Models\User;
use App\Models\UserOtp;

interface UserOtpRepositoryInterface
{
    public function create(User $user, OtpType $type, string $hashedCode): UserOtp;

    public function incrementAttempts(UserOtp $otp): void;

    public function markVerified(UserOtp $otp): void;
}
