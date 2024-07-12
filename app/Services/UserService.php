<?php

namespace App\Services;

use App\Enums\UserEnum;
use App\Http\Requests\RequestLogin;
use App\Jobs\SendForgotPasswordEmail;
use App\Jobs\SendMailNotify;
use App\Models\Channel;
use App\Models\PasswordReset;
use App\Models\User;
use App\Repositories\UserInterface;
use App\Repositories\UserRepository;
use App\Traits\APIResponse;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class UserService
{
    use APIResponse;

    protected UserInterface $userRepository;

    public function __construct(
        UserInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    public function login(RequestLogin $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (empty($user)) {
                return $this->responseError('Email does not exist !');
            }

            // $credentials = (array) $filter;
            $credentials = request(['email', 'password']);
            if (!$token = auth()->guard('user_api')->attempt($credentials)) {
                return $this->responseError('Email or password is incorrect!');
            }
            $user->access_token = $token;
            $user->token_type = 'bearer';
            $user->expires_in = auth()->guard('user_api')->factory()->getTTL() * 60;

            return $this->responseSuccessWithData($user, 'Logged in successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }
    public function profile(Request $request)
    {
        try {
            $user = auth('user_api')->user();

            return $this->responseSuccessWithData($user, 'Get information account successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function userGetMembers(Request $request)
    {
        try {
            $orderBy = $request->typesort ?? 'id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'name';
                    break;

                case 'address':
                    $orderBy = 'address';
                    break;

                case 'gender':
                    $orderBy = 'gender';
                    break;

                case 'phone':
                    $orderBy = 'phone';
                    break;

                case 'new':
                    $orderBy = 'id';
                    break;

                default:
                    $orderBy = 'id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'role' => $request->role ?? 'all',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            // $users = UserRepository::getAllUsers($filter);
            $users = $this->userRepository->getAllUsers($filter);
            if (!(empty($request->paginate))) {
                $users = $users->paginate($request->paginate);
            } else {
                $users = $users->get();
            }

            return $this->responseSuccessWithData($users, 'Get members information successfully!');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

}
