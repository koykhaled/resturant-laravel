<?php

namespace App\Services\OTPActivation;
use App\Services\OTPActivation\OTPActivation;

class ActivationService
{
    protected $otp_activation;
    public function __construct(OTPActivation $otp_activation)
    {
        $this->otp_activation = $otp_activation;
    }

    public function send_otp($recipent)
    {
        $this->otp_activation->send_otp($recipent);
    }

    public function verify_otp($otp)
    {
        $this->otp_activation->verify_otp($otp);
    }
}