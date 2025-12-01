<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    /**
     * Hiển thị danh sách việc làm đã lưu
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        $savedJobs = $user->savedJobs()
            ->with(['company', 'category', 'location'])
            ->orderBy('saved_jobs.created_at', 'desc')
            ->paginate(12);
        
        return view('candidate.saved-jobs.index', compact('savedJobs'));
    }

    /**
     * Lưu việc làm
     */
    public function store($jobId)
    {
        $user = Auth::guard('web')->user();
        $job = Job::findOrFail($jobId);

        // Kiểm tra đã lưu chưa
        if ($user->savedJobs()->where('job_id', $job->id)->exists()) {
            return redirect()->back()
                ->with('info', 'Bạn đã lưu công việc này rồi!');
        }

        $user->savedJobs()->attach($job->id);

        return redirect()->back()
            ->with('success', 'Đã lưu công việc!');
    }

    /**
     * Bỏ lưu việc làm
     */
    public function destroy($jobId)
    {
        $user = Auth::guard('web')->user();
        
        $user->savedJobs()->detach($jobId);

        return redirect()->back()
            ->with('success', 'Đã bỏ lưu công việc!');
    }
}
