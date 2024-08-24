<?php
namespace App\Services\OTPActivation;
use App\Mail\OTPMail;
use App\Models\User;
use App\Services\OTPActivation\OTPActivation;
use Cache;
use Exception;
use Illuminate\Support\Facades\Mail;


class EmailOTPActivation implements OTPActivation
{
    public function send_otp($recipent): void
    {
        $otp = rand(100000, 999999);

        if (!$recipent) {
            throw new Exception("there is no Email to resend code!!", 404);
        }

        Cache::put('otp_' . $recipent, $otp, 3600);

        Mail::to($recipent)->send(new OTPMail($otp));
    }

    public function verify_otp($otp): void
    {
        $email = Cache::get('email');
        $code = Cache::get('otp_' . $email);
        $user = User::whereEmail($email)->first();
        if (!$user) {
            throw new Exception("There Is No User To Activate!!", 404);
        }
        if ($user->is_active === 1) {
            throw new Exception('Your Already Activate your account', 400);
        }
        if ($code !== $otp) {
            throw new Exception("Please Enter a correct code!", 400);
        }
        $user->is_active = true;
        $user->activated_at = now();
        $user->save();
        Cache::forget('otp_' . $email);
        Cache::forget('email');
    }
}