<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Category;
use App\Models\Location;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * Danh sách tin tuyển dụng
     */
    public function index()
    {
        $user = Auth::guard('web')->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('employer.company.edit')
                ->with('error', 'Vui lòng cập nhật thông tin công ty trước khi đăng tin tuyển dụng.');
        }

        $jobs = $company->jobs()->with(['category', 'location', 'skills'])->latest()->paginate(10);

        return view('employer.jobs.index', compact('jobs'));
    }

    /**
     * Form tạo tin mới
     */
    public function create()
    {
        $user = Auth::guard('web')->user();
        
        if (!$user->company) {
            return redirect()->route('employer.company.edit')
                ->with('error', 'Vui lòng cập nhật thông tin công ty trước.');
        }

        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();

        return view('employer.jobs.create', compact('categories', 'locations', 'skills'));
    }

    /**
     * Lưu tin mới
     */
    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();
        $company = $user->company;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'positions' => 'required|integer|min:1|max:999',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'deadline' => 'required|date|after:today',
            'is_featured' => 'boolean',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'location_id.required' => 'Vui lòng chọn địa điểm',
            'salary_max.gte' => 'Lương tối đa phải lớn hơn hoặc bằng lương tối thiểu',
            'positions.required' => 'Vui lòng nhập số lượng tuyển dụng',
            'positions.integer' => 'Số lượng phải là số nguyên',
            'positions.min' => 'Số lượng tối thiểu là 1',
            'positions.max' => 'Số lượng không được vượt quá 999',
            'description.required' => 'Vui lòng nhập mô tả công việc',
            'requirements.required' => 'Vui lòng nhập yêu cầu',
            'deadline.required' => 'Vui lòng chọn hạn nộp hồ sơ',
            'deadline.after' => 'Hạn nộp phải sau ngày hôm nay',
        ]);

        $validated['company_id'] = $company->id;
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['status'] = 'pending'; // Chờ admin duyệt
        $validated['is_featured'] = $request->has('is_featured');

        $job = Job::create($validated);

        // Attach skills
        if ($request->has('skills')) {
            $job->skills()->attach($request->skills);
        }

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Tạo tin tuyển dụng thành công! Tin của bạn đang chờ admin phê duyệt.');
    }

    /**
     * Form sửa tin
     */
    public function edit($id)
    {
        $user = Auth::guard('web')->user();
        $job = Job::where('company_id', $user->company->id)->findOrFail($id);

        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();

        return view('employer.jobs.edit', compact('job', 'categories', 'locations', 'skills'));
    }

    /**
     * Cập nhật tin
     */
    public function update(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $job = Job::where('company_id', $user->company->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'positions' => 'required|integer|min:1|max:999',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'deadline' => 'required|date',
            'is_featured' => 'boolean',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'location_id.required' => 'Vui lòng chọn địa điểm',
            'salary_max.gte' => 'Lương tối đa phải lớn hơn hoặc bằng lương tối thiểu',
            'positions.required' => 'Vui lòng nhập số lượng tuyển dụng',
            'positions.integer' => 'Số lượng phải là số nguyên',
            'positions.min' => 'Số lượng tối thiểu là 1',
            'positions.max' => 'Số lượng không được vượt quá 999',
            'description.required' => 'Vui lòng nhập mô tả công việc',
            'requirements.required' => 'Vui lòng nhập yêu cầu',
            'deadline.required' => 'Vui lòng chọn hạn nộp hồ sơ',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . $job->id;
        $validated['is_featured'] = $request->has('is_featured');
        
        // Nếu đang approved và sửa nội dung quan trọng thì chuyển về pending
        if ($job->status === 'approved' && ($job->title !== $validated['title'] || $job->description !== $validated['description'])) {
            $validated['status'] = 'pending';
        }

        $job->update($validated);

        // Sync skills
        if ($request->has('skills')) {
            $job->skills()->sync($request->skills);
        } else {
            $job->skills()->detach();
        }

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Cập nhật tin tuyển dụng thành công!');
    }

    /**
     * Đóng tin (không nhận hồ sơ nữa)
     */
    public function close($id)
    {
        $user = Auth::guard('web')->user();
        $job = Job::where('company_id', $user->company->id)->findOrFail($id);

        $job->update(['status' => 'closed']);

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Đã đóng tin tuyển dụng.');
    }

    /**
     * Xóa tin
     */
    public function destroy($id)
    {
        $user = Auth::guard('web')->user();
        $job = Job::where('company_id', $user->company->id)->findOrFail($id);

        $job->delete();

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Đã xóa tin tuyển dụng.');
    }
}
