<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard của ứng viên
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        // Thống kê
        $totalApplications = $user->applications()->count();
        $pendingApplications = $user->applications()->where('status', 'pending')->count();
        $interviewApplications = $user->applications()->where('status', 'interview')->count();
        $savedJobsCount = $user->savedJobs()->count();
        
        // Đơn ứng tuyển gần đây (5 đơn mới nhất)
        $recentApplications = $user->applications()
            ->with(['job.company'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('candidate.dashboard', compact(
            'totalApplications',
            'pendingApplications',
            'interviewApplications',
            'savedJobsCount',
            'recentApplications'
        ));
    }
}
