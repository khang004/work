<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    /**
     * Danh sách địa điểm
     */
    public function index()
    {
        $locations = Location::withCount('jobs')->latest()->paginate(20);
        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Form tạo địa điểm
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Lưu địa điểm mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations'],
        ], [
            'name.required' => 'Vui lòng nhập tên địa điểm',
            'name.unique' => 'Địa điểm đã tồn tại',
        ]);

        Location::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Tạo địa điểm thành công!');
    }

    /**
     * Form sửa địa điểm
     */
    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Cập nhật địa điểm
     */
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:locations,name,' . $id],
        ], [
            'name.required' => 'Vui lòng nhập tên địa điểm',
            'name.unique' => 'Địa điểm đã tồn tại',
        ]);

        $location->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.locations.index')->with('success', 'Cập nhật địa điểm thành công!');
    }

    /**
     * Xóa địa điểm
     */
    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        
        if ($location->jobs()->count() > 0) {
            return back()->with('error', 'Không thể xóa địa điểm đang có việc làm!');
        }

        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Xóa địa điểm thành công!');
    }
}
