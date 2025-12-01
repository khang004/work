<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Hiển thị form chỉnh sửa profile
     */
    public function edit()
    {
        $user = Auth::guard('web')->user();
        $skills = Skill::orderBy('name')->get();
        
        return view('candidate.profile.edit', compact('user', 'skills'));
    }

    /**
     * Cập nhật profile
     */
    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'name.max' => 'Họ tên không được vượt quá 255 ký tự',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự',
            'bio.max' => 'Giới thiệu bản thân không được vượt quá 1000 ký tự',
            'skills.*.exists' => 'Kỹ năng không hợp lệ',
        ]);

        // Cập nhật thông tin cơ bản
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        // Cập nhật kỹ năng
        if (isset($validated['skills'])) {
            $user->skills()->sync($validated['skills']);
        } else {
            $user->skills()->detach();
        }

        return redirect()->route('candidate.profile.edit')
            ->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
