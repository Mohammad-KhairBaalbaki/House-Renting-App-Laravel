<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Services\Admin\AdminAuthService;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function __construct(protected AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function show()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $admin = $this->adminAuthService->login($request->validated());

        if (!$admin) {
            return back()
                ->withErrors(['phone' => 'بيانات الدخول غير صحيحة أو ليس لديك صلاحية أدمن'])
                ->withInput();
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $this->adminAuthService->logout($request);
        return redirect()->route('login.show');
    }
}
