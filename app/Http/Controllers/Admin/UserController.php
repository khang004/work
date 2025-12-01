<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Danh sách người dùng
     */
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        
        $query = User::query();
        
        if ($role !== 'all') {
            $query->where('role', $role);
        }
        
        $users = $query->latest()->paginate(20);
        
        return view('admin.users.index', compact('users', 'role'));
    }

    /**
     * Danh sách tài khoản employer chờ duyệt
     */
    public function pendingEmployers()
    {
        $users = User::where('role', 'employer')
            ->where('is_approved', false)
            ->latest()
            ->paginate(20);
            
        return view('admin.users.pending', compact('users'));
    }

    /**
     * Duyệt tài khoản employer
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'employer') {
            return back()->with('error', 'Chỉ có thể duyệt tài khoản nhà tuyển dụng.');
        }
        
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);
        
        // TODO: Gửi email thông báo cho employer
        
        return back()->with('success', "Đã duyệt tài khoản {$user->name}.");
    }

    /**
     * Từ chối tài khoản employer
     */
    public function reject($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role !== 'employer') {
            return back()->with('error', 'Chỉ có thể từ chối tài khoản nhà tuyển dụng.');
        }
        
        // Có thể xóa hoặc giữ lại với trạng thái rejected
        $user->delete();
        
        // TODO: Gửi email thông báo cho employer
        
        return back()->with('success', "Đã từ chối tài khoản {$user->name}.");
    }

    /**
     * Khóa/Mở khóa tài khoản
     */
    public function toggleApproval($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_approved' => !$user->is_approved,
            'approved_at' => !$user->is_approved ? null : now(),
        ]);
        
        $status = $user->is_approved ? 'mở khóa' : 'khóa';
        
        return back()->with('success', "Đã {$status} tài khoản {$user->name}.");
    }
}
