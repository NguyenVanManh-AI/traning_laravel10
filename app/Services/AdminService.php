<?php

namespace App\Services;

use App\Http\Requests\RequestAddManager;
use App\Http\Requests\RequestChangeIsBlock;
use App\Http\Requests\RequestChangeIsBlockMany;
use App\Http\Requests\RequestLogin;
use App\Jobs\SendMailNotify;
use App\Models\Admin;
use App\Models\Channel;
use App\Models\User;
use App\Repositories\AdminInterface;
use App\Repositories\UserInterface;
use App\Repositories\UserRepository;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class AdminService
{
    use APIResponse;

    protected AdminInterface $adminRepository;
    // protected UserInterface $userRepository;

    public function __construct(
        AdminInterface $adminRepository,
        // UserInterface $userRepository,
    ) {
        $this->adminRepository = $adminRepository;
        // $this->userRepository = $userRepository;
    }

    public function login(RequestLogin $request)
    {
        try {
            $admin = Admin::where('email', $request->email)->first();
            if (empty($admin)) {
                return $this->responseError('Email does not exist !', 404);
            }
            $credentials = request(['email', 'password']);
            if (!$token = auth()->guard('admin_api')->attempt($credentials)) {
                return $this->responseError('Email or password is incorrect!');
            }
            $admin->access_token = $token;
            $admin->token_type = 'bearer';
            $admin->expires_in = auth()->guard('admin_api')->factory()->getTTL() * 60;

            return $this->responseSuccessWithData($admin, 'Logged in successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            auth('admin_api')->logout();

            return $this->responseSuccess('Log out successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function profile(Request $request)
    {
        try {
            $admin = auth('admin_api')->user();

            return $this->responseSuccessWithData($admin, 'Get information account successfully !');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function adminGetMembers(Request $request)
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
                'is_delete' => $request->is_delete ?? 'all',
                'is_block' => $request->is_block ?? 'all',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            $managers = UserRepository::getAllUsers($filter);
            // $managers = $this->userRepository->getAllUsers($filter);
            if (!(empty($request->paginate))) {
                $managers = $managers->paginate($request->paginate);
            } else {
                $managers = $managers->get();
            }

            return $this->responseSuccessWithData($managers, 'Get members information successfully!');
        } catch (Throwable $e) {
            return $this->responseError($e->getMessage());
        }
    }
}
