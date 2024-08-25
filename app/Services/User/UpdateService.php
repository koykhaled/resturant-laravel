<?php
namespace App\Services\User;
use App\Services\OTPActivation\ActivationService;
use App\Services\OTPActivation\EmailOTPActivation;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Facades\JWTAuth;


class UpdateService
{

    public function update_profile($request)
    {
        $user = auth()->user()->load('addresses');
        $this->update_user($request, $user);
        $this->create_or_update_address($request, $user);

        return $user;
    }

    protected function update_user($request, $user)
    {
        $email = $user->email;
        $user->update([
            'full_name' => $request->full_name ?? $user->full_name,
            'email' => $request->email ?? $user->email,
            'phone' => $request->phone ?? $user->phone,
            'avatar' => $request->avatar ?? $user->avatar,
        ]);
        if ($email !== $user->email) {
            Cache::put('email', $user->email);
            $email_otp_activation = new EmailOTPActivation();
            $activation_service = new ActivationService($email_otp_activation);
            $activation_service->send_otp($user->email);
            $user->is_active = 0;
            $user->activated_at = null;
            $user->save();
            JWTAuth::invalidate(JWTAuth::getToken());
        }
    }

    protected function create_or_update_address($request, $user)
    {

        if ($request->has('address')) {
            $address = $user->addresses()->where('type', $request->address['type'])->first();

            if ($address) {
                $address->update([
                    'city' => $request->address['city'] ?? $address->city,
                    'country' => $request->address['country'] ?? $address->country,
                    'street' => $request->address['street'] ?? $address->street,
                    'state' => $request->address['state'] ?? $address->state,
                    'post_code' => $request->address['post_code'] ?? $address->post_code,
                ]);
            } else {
                $user->addresses()->create([
                    'type' => $request->address['type'],
                    'city' => $request->address['city'],
                    'country' => $request->address['country'],
                    'street' => $request->address['street'],
                    'state' => $request->address['state'],
                    'post_code' => $request->address['post_code'],
                ]);
            }
        }
    }
}