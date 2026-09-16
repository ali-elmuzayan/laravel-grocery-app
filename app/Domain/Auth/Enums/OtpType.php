<?php

namespace App\Domain\Auth\Enums;

enum OtpType: string
{
    case ForgotPassword = 'forgot_password';
    case VerifyEmail = 'verify_email';
}
