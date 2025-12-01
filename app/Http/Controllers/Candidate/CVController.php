<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CVController extends Controller
{
    /**
     * Hiển thị trang quản lý CV
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        return view('candidate.cv.index', compact('user'));
    }

    /**
     * Upload CV
     */
    public function upload(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ], [
            'cv.required' => 'Vui lòng chọn file CV',
            'cv.file' => 'File không hợp lệ',
            'cv.mimes' => 'CV phải có định dạng: PDF, DOC, DOCX',
            'cv.max' => 'CV không được vượt quá 5MB',
        ]);

        // Xóa CV cũ nếu có
        if ($user->cv_path) {
            Storage::disk('public')->delete($user->cv_path);
        }

        // Upload CV mới
        $path = $request->file('cv')->store('cvs', 'public');
        
        $user->update(['cv_path' => $path]);

        return redirect()->route('candidate.cv.index')
            ->with('success', 'Upload CV thành công!');
    }

    /**
     * Xóa CV
     */
    public function destroy()
    {
        $user = Auth::guard('web')->user();

        if ($user->cv_path) {
            Storage::disk('public')->delete($user->cv_path);
            $user->update(['cv_path' => null]);
            
            return redirect()->route('candidate.cv.index')
                ->with('success', 'Xóa CV thành công!');
        }

        return redirect()->route('candidate.cv.index')
            ->with('error', 'Không có CV để xóa!');
    }

    /**
     * Tải xuống CV
     */
    public function download()
    {
        $user = Auth::guard('web')->user();

        if (!$user->cv_path || !Storage::disk('public')->exists($user->cv_path)) {
            return redirect()->route('candidate.cv.index')
                ->with('error', 'CV không tồn tại!');
        }

        $filePath = Storage::disk('public')->path($user->cv_path);
        $fileName = 'CV_' . $user->name . '.' . pathinfo($user->cv_path, PATHINFO_EXTENSION);
        
        return response()->download($filePath, $fileName);
    }
}
