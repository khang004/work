<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Hiển thị/Sửa thông tin công ty
     */
    public function edit()
    {
        $user = Auth::guard('web')->user();
        $company = $user->company;

        // Nếu chưa có công ty, tạo mới
        if (!$company) {
            $company = Company::create([
                'user_id' => $user->id,
                'name' => 'Công ty của ' . $user->name,
            ]);
        }

        return view('employer.company.edit', compact('company'));
    }

    /**
     * Cập nhật thông tin công ty
     */
    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();
        $company = $user->company;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'size' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập tên công ty',
            'website.url' => 'Website không hợp lệ',
            'logo.image' => 'Logo phải là file ảnh',
            'logo.mimes' => 'Logo phải có định dạng: jpeg, png, jpg, gif',
            'logo.max' => 'Logo không được vượt quá 2MB',
        ]);

        // Xử lý upload logo
        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $path;
        }

        $company->update($validated);

        return redirect()->route('employer.company.edit')
            ->with('success', 'Cập nhật thông tin công ty thành công!');
    }
}
