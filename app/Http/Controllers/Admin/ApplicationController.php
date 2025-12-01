<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đơn ứng tuyển
     */
    public function index(Request $request)
    {
        $query = Application::with(['user', 'job.company', 'job.category', 'job.location']);

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tìm theo tên ứng viên
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->whereHas('user', function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo công việc
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->latest()->paginate(20);

        // Thống kê
        $totalApplications = Application::count();
        $pendingCount = Application::where('status', 'pending')->count();
        $reviewingCount = Application::where('status', 'reviewing')->count();
        $interviewCount = Application::where('status', 'interview')->count();
        $hiredCount = Application::where('status', 'hired')->count();
        $rejectedCount = Application::where('status', 'rejected')->count();

        return view('admin.applications.index', compact(
            'applications',
            'totalApplications',
            'pendingCount',
            'reviewingCount',
            'interviewCount',
            'hiredCount',
            'rejectedCount'
        ));
    }

    /**
     * Xem chi tiết đơn ứng tuyển
     */
    public function show($id)
    {
        $application = Application::with(['user.skills', 'job.company', 'job.category', 'job.location', 'job.skills'])
            ->findOrFail($id);

        return view('admin.applications.show', compact('application'));
    }

    /**
     * Cập nhật trạng thái đơn ứng tuyển
     */
    public function updateStatus(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,interview,hired,rejected',
            'note' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái không hợp lệ',
            'note.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ]);

        $application->update($validated);

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái đơn ứng tuyển thành công!');
    }

    /**
     * Xóa đơn ứng tuyển
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Đã xóa đơn ứng tuyển!');
    }
}
