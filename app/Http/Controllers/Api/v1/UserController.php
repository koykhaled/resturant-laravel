<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\User\UpdateService;
use App\Traits\ApiResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    //
    use ApiResponse;

    protected $update_service;
    public function __construct(UpdateService $update_service)
    {
        $this->update_service = $update_service;
    }


    public function update(UpdateUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->update_service->update_profile($request);
            DB::commit();
            return $this->successResponse($data, "Updated Successful", 200);

        } catch (Exception $th) {
            DB::rollBack();
            return $this->errorResponse($th->getMessage(), 400);
        }
    }
}