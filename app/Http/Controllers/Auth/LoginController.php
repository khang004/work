<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        // Không remember me - session sẽ hết khi đóng browser
        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Kiểm tra tài khoản employer đã được duyệt chưa
            if ($user->isEmployer() && !$user->isApproved()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đang chờ admin phê duyệt. Vui lòng kiên nhẫn chờ đợi.',
                ])->onlyInput('email');
            }

            // Chuyển hướng theo vai trò
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isEmployer()) {
                return redirect()->intended('/employer/dashboard');
            } else {
                return redirect()->intended('/candidate/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Hiển thị form đăng nhập Admin
     */
    public function showAdminLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Xử lý đăng nhập Admin
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        // Sử dụng guard 'admin' riêng - không remember me
        if (Auth::guard('admin')->attempt($credentials, false)) {
            $user = Auth::guard('admin')->user();

            // Chỉ cho phép admin đăng nhập
            if (!$user->isAdmin()) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Bạn không có quyền truy cập trang quản trị.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Đăng xuất User (Candidate/Employer)
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Đăng xuất Admin
     */
    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}
