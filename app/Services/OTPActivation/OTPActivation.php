<?php

namespace App\Services\OTPActivation;

interface OTPActivation
{
    public function send_otp($recipent): void;
    public function verify_otp($otp): void;
}