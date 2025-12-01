<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard của admin
     */
    public function index()
    {
        // Thống kê tổng quan
        $totalUsers = User::count();
        $totalCandidates = User::where('role', 'candidate')->count();
        $totalEmployers = User::where('role', 'employer')->count();
        $pendingEmployers = User::where('role', 'employer')->where('is_approved', false)->count();
        $totalJobs = Job::count();
        $activeJobs = Job::where('status', 'approved')->where('deadline', '>=', now())->count();
        $pendingJobs = Job::where('status', 'pending')->count();
        $totalApplications = Application::count();
        $totalCompanies = Company::count();

        // Việc làm mới cần duyệt
        $pendingJobsList = Job::with(['company', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();
            
        // Tài khoản employer chờ duyệt
        $pendingEmployersList = User::where('role', 'employer')
            ->where('is_approved', false)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalCandidates',
            'totalEmployers',
            'pendingEmployers',
            'totalJobs',
            'activeJobs',
            'pendingJobs',
            'totalApplications',
            'totalCompanies',
            'pendingJobsList',
            'pendingEmployersList'
        ));
    }
}
