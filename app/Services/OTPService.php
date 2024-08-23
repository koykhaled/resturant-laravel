<?php

namespace App\Services;

use Twilio\Rest\Client;

class OTPService
{

    protected $sid;
    protected $token;

    public function __construct()
    {
        $this->sid = getenv('TWILIO_SID');
        $this->token = getenv('TWILIO_TOKEN');
    }


    public function send_otp($reciver_phone)
    {
        $twilio = new Client($this->sid, $this->token);
        $twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE_SID'))
            ->verifications
            ->create($reciver_phone, "sms");
    }

    public function otp_verification($code, $phone)
    {
        $twilio = new Client($this->sid, $this->token);
        $verificationCheck = $twilio->verify->v2->services(env('TWILIO_VERIFY_SERVICE_SID'))
            ->verificationChecks
            ->create(["code" => $code, "to" => $phone]);
        return $verificationCheck;
    }

}