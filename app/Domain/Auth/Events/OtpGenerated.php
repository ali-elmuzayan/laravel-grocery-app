<?php

namespace App\Domain\Auth\Events;

use App\Domain\Auth\Enums\OtpType;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpGenerated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public string $code,
        public OtpType $type,
    ) {}
}
