<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Throwable;

class AdminController extends Controller
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

            // kiểm tra email có tồn tại ? 
            $admin = Admin::where('email', $request->email)->first();
            if (empty($admin)) {
                return $this->responseError(400, 'Email không tồn tại !');
            }


            // kiểm tra mật khẩu có đúng ? 
            // $data = request(['email', 'password']);  // C1 

            // C2 
            $data = [
                "email" => $request->email,
                "password" => $request->password,
            ];

            if (!auth()->guard('admin_api')->attempt($data)) {
                return $this->responseError(400, 'Email hoặc mật khẩu không đúng !');
            }

            // trả về thông tin nếu toàn bộ đã đúng 
            $admin->access_token = auth()->guard('admin_api')->attempt($data);
            $admin->token_type = 'bearer';
            $admin->expires_in = auth()->guard('admin_api')->factory()->getTTL() * 60;
            // $admin->abc = [123,'avv'];
            // $admin->a1 = 'a1';

            return $this->responseOK(200, $admin, 'Đăng nhập thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function profile()
    {
        $admin = auth('admin_api')->user();

        return response()->json([
            'message' => 'Xem thông tin cá nhân thành công !',
            'data' => $admin,
            'status' => 200,
        ], 200);
    }
}
