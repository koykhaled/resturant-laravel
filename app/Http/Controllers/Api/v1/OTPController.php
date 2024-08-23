<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OTPService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class OTPController extends Controller
{
    //
    use ApiResponse;
    protected $otp_service;
    public function __construct(OTPService $otp_service)
    {
        $this->otp_service = $otp_service;
    }

    public function resend_otp()
    {
        try {
            $phone = Cache::get('phone');
            $this->otp_service->send_otp($phone);

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
        $user_id = Cache::get('user_id');
        $user = User::find($user_id);

        $verification = $this->otp_service->otp_verification($request->code, $user->phone);
        if ($verification->status === "approved") {
            $user->is_active = true;
            $user->activated_at = now();
            $user->save();
        } else {
            dd("wrong");
        }
        return response()->json(["message" => "verified done"], 200);
    }
}