<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Lấy việc làm nổi bật
        $featuredJobs = Job::with(['company', 'category', 'location'])
            ->where('status', 'approved')
            ->where('is_featured', true)
            ->where('deadline', '>=', now())
            ->latest()
            ->take(6)
            ->get();

        // Lấy việc làm mới nhất
        $latestJobs = Job::with(['company', 'category', 'location'])
            ->where('status', 'approved')
            ->where('deadline', '>=', now())
            ->latest()
            ->take(12)
            ->get();

        // Lấy công ty hàng đầu (có nhiều việc làm nhất)
        $topCompanies = Company::withCount(['jobs' => function ($query) {
                $query->where('status', 'approved')
                    ->where('deadline', '>=', now());
            }])
            ->having('jobs_count', '>', 0)
            ->orderBy('jobs_count', 'desc')
            ->take(8)
            ->get();

        // Lấy danh sách categories và locations cho form tìm kiếm
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('guest.home', compact('featuredJobs', 'latestJobs', 'topCompanies', 'categories', 'locations'));
    }
}
