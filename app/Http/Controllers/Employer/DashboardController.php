<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard của nhà tuyển dụng
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('employer.company.edit')
                ->with('info', 'Vui lòng cập nhật thông tin công ty trước khi sử dụng.');
        }

        // Thống kê
        $totalJobs = $company->jobs()->count();
        $activeJobs = $company->jobs()->where('status', 'approved')->where('deadline', '>=', now())->count();
        $pendingJobs = $company->jobs()->where('status', 'pending')->count();
        $totalApplications = $company->jobs()->withCount('applications')->get()->sum('applications_count');

        return view('employer.dashboard', compact(
            'company',
            'totalJobs',
            'activeJobs',
            'pendingJobs',
            'totalApplications'
        ));
    }
}
