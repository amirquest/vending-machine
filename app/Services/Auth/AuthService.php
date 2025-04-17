<?php

namespace App\Services\Auth;

use Fouladgar\OTP\Contracts\OTPNotifiable;
use Fouladgar\OTP\Exceptions\InvalidOTPTokenException;
use Fouladgar\OTP\Notifications\Messages\OTPMessage;
use Fouladgar\OTP\Notifications\OTPNotification;
use Fouladgar\OTP\OTPBroker as OTPService;
use Throwable;

readonly class AuthService
{
    public function __construct(private OTPService $OTPService)
    {
        OTPNotification::toSMSUsing(fn($notifiable, $token) => (new OTPMessage())
            ->to($notifiable->mobile)
            ->content($token));
    }

    /**
     * @throws Throwable
     */
    public function sendOtp(string $mobile, string $provider = 'users'): OTPNotifiable
    {
        $mustExists = false;
        $otp = $this->OTPService->useProvider($provider);

        if($provider === 'admins') {
            $mustExists = true;
        }

        return $otp->send($mobile, $mustExists);
    }

    /**
     * @throws InvalidOTPTokenException|Throwable
     */
    public function verifyOtp(string $mobile, string $token, string $provider = 'users'): OTPNotifiable
    {
        return $this->OTPService->useProvider($provider)->validate($mobile, $token);
    }
}
