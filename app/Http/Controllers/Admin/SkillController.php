<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkillController extends Controller
{
    /**
     * Danh sách kỹ năng
     */
    public function index()
    {
        $skills = Skill::withCount('jobs')->latest()->paginate(20);
        return view('admin.skills.index', compact('skills'));
    }

    /**
     * Form tạo kỹ năng
     */
    public function create()
    {
        return view('admin.skills.create');
    }

    /**
     * Lưu kỹ năng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:skills'],
        ], [
            'name.required' => 'Vui lòng nhập tên kỹ năng',
            'name.unique' => 'Kỹ năng đã tồn tại',
        ]);

        Skill::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Tạo kỹ năng thành công!');
    }

    /**
     * Form sửa kỹ năng
     */
    public function edit($id)
    {
        $skill = Skill::findOrFail($id);
        return view('admin.skills.edit', compact('skill'));
    }

    /**
     * Cập nhật kỹ năng
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:skills,name,' . $id],
        ], [
            'name.required' => 'Vui lòng nhập tên kỹ năng',
            'name.unique' => 'Kỹ năng đã tồn tại',
        ]);

        $skill->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Cập nhật kỹ năng thành công!');
    }

    /**
     * Xóa kỹ năng
     */
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        
        if ($skill->jobs()->count() > 0) {
            return back()->with('error', 'Không thể xóa kỹ năng đang được sử dụng!');
        }

        $skill->delete();

        return redirect()->route('admin.skills.index')->with('success', 'Xóa kỹ năng thành công!');
    }
}
