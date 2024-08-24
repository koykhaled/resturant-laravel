<?php
namespace App\Services\Auth;
use App\Models\User;
use App\Services\OTPActivation\ActivationService;
use App\Services\OTPActivation\EmailOTPActivation;
use App\Services\OTPService;
use Cache;

class RegisterService
{

    public function sign_up($request)
    {
        $full_name = $request->input('full_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('password');

        $user = $this->create_user($full_name, $email, $phone, $password);
        $this->cashe_user_data($user);
        $this->send_otp($email);

        return [
            "message" => "Registerd Successful",
            "status" => 200
        ];
    }

    public function create_user($full_name, $email, $phone, $password)
    {
        $user = User::create([
            'full_name' => $full_name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password
        ]);
        return $user;
    }

    protected function cashe_user_data($user)
    {
        Cache::put("phone", $user->phone, 3600);
        Cache::put("email", $user->email, 3600);
    }

    public function send_otp($email)
    {
        // well extend when we need to provide sms activation


        $email_otp_activation = new EmailOTPActivation();
        $activation_service = new ActivationService($email_otp_activation);
        $activation_service->send_otp($email);
    }
}