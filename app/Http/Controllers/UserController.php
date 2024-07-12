<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestLogin;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordSendCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendForgotPasswordEmail;
use App\Enums\UserEnum;

class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(RequestLogin $request)
    {
        // $filter = [
            // 'email' => $request->email,
            // 'password' => $request->password
        // ];
        return $this->userService->login($request);
    }

    public function profile(Request $request)
    {
        return $this->userService->profile($request);
    }

    public function userGetMembers(Request $request)
    {
        return $this->userService->userGetMembers($request);
    }

}
