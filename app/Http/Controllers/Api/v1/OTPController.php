<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\OTPActivation\ActivationService;
use App\Services\OTPActivation\EmailOTPActivation;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class OTPController extends Controller
{
    //
    use ApiResponse;


    public function resend_otp()
    {
        try {
            $email = Cache::get('email');

            Cache::forget('otp_' . $email);
            $email_otp_activation = new EmailOTPActivation();
            $activation_service = new ActivationService($email_otp_activation);
            $activation_service->send_otp($email);

            $message = "Resend Verification Code successful";
            $status = 200;

            return $this->successResponse(null, $message, $status);


        } catch (ValidationException $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());

        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }

    }

    public function verify_otp(Request $request)
    {
        try {
            $email_otp_activation = new EmailOTPActivation();
            $activation_service = new ActivationService($email_otp_activation);
            $activation_service->verify_otp($request->code);

            return $this->successResponse(null, "Verified Successful", 200);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }

    }
}