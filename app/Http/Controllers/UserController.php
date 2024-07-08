<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{
    public function responseOK($status = 200, $data = null, $message = '')
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }

    public function responseError($status = 400, $message = '')
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    // public function login(RequestLogin $request)
    public function login(Request $request)
    {
        try {

            $admin = User::where('email', $request->email)->first();
            if (empty($admin)) {
                return $this->responseError(400, 'Email không tồn tại !');
            }

            $data = request(['email', 'password']);  

            if (!auth()->guard('user_api')->attempt($data)) {
                return $this->responseError(400, 'Email hoặc mật khẩu không đúng !');
            }
            $admin->access_token = auth()->guard('user_api')->attempt($data);
            $admin->token_type = 'bearer';
            $admin->expires_in = auth()->guard('user_api')->factory()->getTTL() * 60;

            return $this->responseOK(200, $admin, 'Đăng nhập thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function profile()
    {
        $user = auth('user_api')->user();

        return response()->json([
            'message' => 'Xem thông tin cá nhân thành công !',
            'data' => $user,
            'status' => 200,
        ], 200);
    }
}
