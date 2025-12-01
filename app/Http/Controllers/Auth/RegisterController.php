<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Hiển thị form đăng ký
     */
    public function showRegistrationForm(Request $request)
    {
        $role = $request->get('role', 'candidate'); // Mặc định là ứng viên
        return view('auth.register', compact('role'));
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:candidate,employer'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_approved' => $request->role === 'candidate' ? true : false, // Employer cần admin duyệt
        ]);

        // Chuyển hướng theo vai trò
        if ($user->isEmployer()) {
            // Employer không được đăng nhập ngay, chờ admin duyệt
            return redirect()->route('login')->with('info', 'Đăng ký thành công! Tài khoản của bạn đang chờ admin phê duyệt. Bạn sẽ nhận được thông báo qua email khi tài khoản được kích hoạt.');
        } else {
            // Candidate được đăng nhập ngay
            Auth::login($user);
            return redirect('/candidate/dashboard')->with('success', 'Đăng ký thành công!');
        }
    }
}
