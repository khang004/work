<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Hiển thị danh sách việc đã ứng tuyển
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        $applications = Application::where('user_id', $user->id)
            ->with(['job.company', 'job.category', 'job.location'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('candidate.applications.index', compact('applications'));
    }

    /**
     * Hiển thị chi tiết đơn ứng tuyển
     */
    public function show($id)
    {
        $user = Auth::guard('web')->user();
        
        $application = Application::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['job.company', 'job.category', 'job.location'])
            ->firstOrFail();
        
        return view('candidate.applications.show', compact('application'));
    }

    /**
     * Ứng tuyển vào một công việc
     */
    public function apply(Request $request, $jobId)
    {
        $user = Auth::guard('web')->user();
        $job = Job::findOrFail($jobId);

        // Kiểm tra CV đã upload chưa
        if (!$user->cv_path) {
            return redirect()->back()
                ->with('error', 'Vui lòng upload CV trước khi ứng tuyển!');
        }

        // Kiểm tra đã ứng tuyển chưa
        $existingApplication = Application::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()
                ->with('error', 'Bạn đã ứng tuyển vào công việc này rồi!');
        }

        // Kiểm tra job còn nhận hồ sơ không
        if ($job->status !== 'approved' || $job->deadline < now()) {
            return redirect()->back()
                ->with('error', 'Công việc này không còn nhận hồ sơ!');
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
        ], [
            'cover_letter.max' => 'Thư giới thiệu không được vượt quá 2000 ký tự',
        ]);

        // Tạo đơn ứng tuyển
        Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'cv_path' => $user->cv_path,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Ứng tuyển thành công! Nhà tuyển dụng sẽ xem xét hồ sơ của bạn.');
    }

    /**
     * Hủy đơn ứng tuyển (chỉ khi status = pending)
     */
    public function cancel($id)
    {
        $user = Auth::guard('web')->user();
        
        $application = Application::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($application->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Không thể hủy đơn ứng tuyển đã được xử lý!');
        }

        $application->delete();

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Đã hủy đơn ứng tuyển!');
    }
}
