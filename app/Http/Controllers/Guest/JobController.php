<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Tìm kiếm việc làm
     */
    public function search(Request $request)
    {
        $query = Job::with(['company', 'category', 'location'])
            ->where('status', 'approved')
            ->where('deadline', '>=', now());

        // Tìm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo địa điểm
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        $jobs = $query->latest()->paginate(20);
        $categories = Category::all();
        $locations = Location::all();

        return view('guest.jobs.search', compact('jobs', 'categories', 'locations'));
    }

    /**
     * Xem chi tiết việc làm
     */
    public function show($slug)
    {
        $job = Job::with(['company', 'category', 'location', 'skills'])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        // Việc làm liên quan (cùng danh mục)
        $relatedJobs = Job::with(['company', 'category', 'location'])
            ->where('category_id', $job->category_id)
            ->where('id', '!=', $job->id)
            ->where('status', 'approved')
            ->where('deadline', '>=', now())
            ->take(5)
            ->get();

        return view('guest.jobs.show', compact('job', 'relatedJobs'));
    }
}
