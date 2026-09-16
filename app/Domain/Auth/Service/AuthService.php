<?php

namespace App\Domain\Auth\Service;

use App\Domain\Auth\Contracts\UserOtpRepositoryInterface;
use App\Domain\Auth\Contracts\UserRepositoryInterface;
use App\Domain\Auth\DTOs\LoginData;
use App\Domain\Auth\DTOs\RegisterData;
use App\Domain\Auth\Enums\OtpType;
use App\Domain\Auth\Events\OtpGenerated;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private const RESET_WINDOW_MINUTES = 15;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserOtpRepositoryInterface $userOtpRepository,
    ) {}

    public function register(RegisterData $data): User
    {
        $user = $this->userRepository->create($data);

        event(new Registered($user));
        $this->generateOtp($user, OtpType::VerifyEmail);

        return $user;
    }

    public function login(LoginData $data): array
    {
        if (! Auth::attempt($data)) {
            throw new AuthenticationException('Invalid credentials');
        }

        $token = Auth::user()->createToken('auth_token')->plainTextToken;

        return [
            'user' => Auth::user(),
            'token' => $token,
        ];
    }

    public function forgetPassword(string $email): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'User not found',
            ]);
        }

        $this->generateOtp($user, OtpType::ForgotPassword);

        return $user;
    }

    public function verifyOtp(string $email, string $code, OtpType $type): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'User not found',
            ]);
        }

        $this->validateOtp($user, $code, $type);

        return $user;
    }

    public function resendOtp(string $email, OtpType $type): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'User not found',
            ]);
        }

        $this->generateOtp($user, $type);

        return $user;
    }

    public function resetPassword(string $email, string $password): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'User not found',
            ]);
        }

        $verifiedOtp = $this->userOtpRepository->findLatestVerified(
            $user,
            OtpType::ForgotPassword,
            self::RESET_WINDOW_MINUTES,
        );

        if (! $verifiedOtp) {
            throw ValidationException::withMessages([
                'email' => 'Please verify your OTP before resetting your password.',
            ]);
        }

        $user->password = $password;
        $this->userRepository->update($user);

        return $user;
    }

    private function generateOtp(User $user, OtpType $type): string
    {
        $plainCode = (string) random_int(100000, 999999);

        $this->userOtpRepository->invalidatePending($user, $type);
        $this->userOtpRepository->create($user, $type, Hash::make($plainCode));

        event(new OtpGenerated($user, $plainCode, $type));

        return $plainCode;
    }

    private function validateOtp(User $user, string $code, OtpType $type): void
    {
        $otp = $this->userOtpRepository->findLatestPending($user, $type);

        if (! $otp) {
            throw ValidationException::withMessages([
                'otp' => 'OTP is invalid or has expired.',
            ]);
        }

        if ($otp->hasExceededAttempts()) {
            throw ValidationException::withMessages([
                'otp' => 'Too many failed attempts. Please request a new OTP.',
            ]);
        }

        if (! Hash::check($code, $otp->code)) {
            $this->userOtpRepository->incrementAttempts($otp);

            throw ValidationException::withMessages([
                'otp' => 'OTP is invalid or has expired.',
            ]);
        }

        $this->userOtpRepository->markVerified($otp);

        if ($type === OtpType::VerifyEmail) {
            $user->email_verified_at = now();
            $this->userRepository->update($user);
        }
    }
}
