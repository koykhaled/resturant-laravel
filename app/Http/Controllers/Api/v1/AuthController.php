<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Auth\LoginService;
use App\Services\Auth\RegisterService;
use App\Services\OTPService;
use App\Traits\ApiResponse;
use Cache;
use DB;
use Exception;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;
    protected $register_service;
    protected $login_service;
    public function __construct(RegisterService $register_service, LoginService $login_service)
    {
        $this->register_service = $register_service;
        $this->login_service = $login_service;
    }
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $register = $this->register_service->sign_up($request);
            DB::commit();

            return $this->successResponse(null, $register['message'], $register['status']);
        } catch (ValidationException $th) {
            DB::rollBack();
            return $this->errorResponse($th->getMessage(), 400);

        } catch (Exception $th) {
            DB::rollBack();
            return $this->errorResponse("ERROR->" . $th->getMessage(), 500);
        }
    }



    public function me()
    {
        $user = auth()->user();
        return $this->successResponse($user, "user data", 200);
    }


    public function login(LoginRequest $request)
    {
        try {

            $data = $this->login_service->authenticate($request);

            return $this->successResponse($data, "Login Successful", 200);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }



}