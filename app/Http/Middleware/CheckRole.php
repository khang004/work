<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Role cần kiểm tra (admin, employer, candidate)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Chọn guard phù hợp với role
        $guard = $role === 'admin' ? 'admin' : 'web';
        
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!auth($guard)->check()) {
            // Redirect về trang login tương ứng với role
            $loginRoute = $role === 'admin' ? 'admin.login' : 'login';
            return redirect()->route($loginRoute)->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        $user = auth($guard)->user();

        // Kiểm tra role của người dùng
        if ($user->role !== $role) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Kiểm tra tài khoản employer đã được duyệt chưa
        if ($user->role === 'employer' && !$user->is_approved) {
            auth()->guard($guard)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đang chờ admin phê duyệt.');
        }

        return $next($request);
    }
}
