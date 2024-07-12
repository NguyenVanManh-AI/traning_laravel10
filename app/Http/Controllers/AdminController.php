<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAddManager;
use App\Http\Requests\RequestChangeIsBlock;
use App\Http\Requests\RequestChangeIsBlockMany;
use App\Http\Requests\RequestLogin;
use App\Services\AdminService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    public function login(RequestLogin $request)
    {
        return $this->adminService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->adminService->logout($request);
    }

    public function profile(Request $request)
    {
        return $this->adminService->profile($request);
    }

    public function adminGetMembers(Request $request)
    {
        return $this->adminService->adminGetMembers($request);
    } 
}
