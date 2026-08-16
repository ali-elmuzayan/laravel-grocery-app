<?php

namespace App\Domain\Auth\Listeners;

use App\Domain\Auth\Events\OtpGenerated;

class SendOtpEmail
{
    public function handle(OtpGenerated $event): void
    {
        // Mail::to($event->user->email)->send(new OtpMail($event->code, $event->type));
    }
}
