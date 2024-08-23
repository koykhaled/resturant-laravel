<?php
namespace App\Services\Auth;
use App\Models\User;
use App\Services\OTPService;
use Cache;

class RegisterService
{
    protected $otp_service;
    public function __construct(OTPService $otp_service)
    {
        $this->otp_service = $otp_service;
    }
    public function sign_up($request)
    {
        $full_name = $request->input('full_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('password');

        $user = $this->create_user($full_name, $email, $phone, $password);
        $this->cashe_user_data($user);
        $this->send_otp($phone);

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

    public function cashe_user_data($user)
    {
        Cache::put("user_id", $user->id, 600);
        Cache::put("phone", $user->phone, 600);
    }

    public function send_otp($phone)
    {
        $this->otp_service->send_otp($phone);
    }
}