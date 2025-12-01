<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Danh sách tất cả việc làm
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $query = Job::with(['company', 'category', 'location']);
        
        // Tìm kiếm theo từ khóa
        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }
        
        // Lọc theo trạng thái
        if ($status) {
            $query->where('status', $status);
        }
        
        $jobs = $query->latest()->paginate(20);
        
        // Thống kê
        $stats = [
            'total' => Job::count(),
            'pending' => Job::where('status', 'pending')->count(),
            'approved' => Job::where('status', 'approved')->count(),
            'closed' => Job::where('status', 'closed')->count(),
        ];
        
        return view('admin.jobs.index', compact('jobs', 'stats'));
    }

    /**
     * Danh sách việc làm chờ duyệt
     */
    public function pending()
    {
        $jobs = Job::with(['company', 'category', 'location'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);
            
        return view('admin.jobs.pending', compact('jobs'));
    }

    /**
     * Xem chi tiết việc làm
     */
    public function show($id)
    {
        $job = Job::with(['company', 'category', 'location', 'skills'])->findOrFail($id);
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Duyệt việc làm
     */
    public function approve($id)
    {
        $job = Job::findOrFail($id);
        
        $job->update(['status' => 'approved']);
        
        // TODO: Gửi email thông báo cho employer
        
        return back()->with('success', "Đã duyệt tin tuyển dụng: {$job->title}");
    }

    /**
     * Từ chối việc làm
     */
    public function reject($id)
    {
        $job = Job::findOrFail($id);
        
        $job->update(['status' => 'closed']);
        
        // TODO: Gửi email thông báo cho employer
        
        return back()->with('success', "Đã từ chối tin tuyển dụng: {$job->title}");
    }

    /**
     * Đóng việc làm
     */
    public function close($id)
    {
        $job = Job::findOrFail($id);
        
        $job->update(['status' => 'closed']);
        
        return back()->with('success', "Đã đóng tin tuyển dụng: {$job->title}");
    }

    /**
     * Xóa việc làm
     */
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        
        $job->delete();
        
        return redirect()->route('admin.jobs.index')->with('success', 'Đã xóa tin tuyển dụng!');
    }
}
