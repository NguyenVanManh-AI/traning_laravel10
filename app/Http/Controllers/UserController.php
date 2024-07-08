<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestUserLogin;
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
    public function login(RequestUserLogin $request)
    {
        try {

            // // quick validate 
            // $request->validate([
            //     'email' => 'required|email',
            //     'password' => 'required',
            // ]);

            $user = User::where('email', $request->email)->first();
            if (empty($user)) {
                return $this->responseError(400, 'Email không tồn tại !');
            }

            $data = request(['email', 'password']);  

            if (!auth()->guard('user_api')->attempt($data)) {
                return $this->responseError(400, 'Email hoặc mật khẩu không đúng !');
            }
            $user->access_token = auth()->guard('user_api')->attempt($data);
            $user->token_type = 'bearer';
            $user->expires_in = auth()->guard('user_api')->factory()->getTTL() * 60;

            return $this->responseOK(200, $user, 'Đăng nhập thành công !');
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
