<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Công nghệ thông tin', 'description' => 'Lập trình, phát triển phần mềm, IT'],
            ['name' => 'Marketing', 'description' => 'Marketing, quảng cáo, truyền thông'],
            ['name' => 'Kế toán - Kiểm toán', 'description' => 'Kế toán, kiểm toán, tài chính'],
            ['name' => 'Nhân sự', 'description' => 'Quản lý nhân sự, tuyển dụng'],
            ['name' => 'Bán hàng', 'description' => 'Bán hàng, kinh doanh, chăm sóc khách hàng'],
            ['name' => 'Thiết kế', 'description' => 'Thiết kế đồ họa, UI/UX, sáng tạo'],
            ['name' => 'Giáo dục - Đào tạo', 'description' => 'Giảng dạy, đào tạo, giáo dục'],
            ['name' => 'Y tế - Dược', 'description' => 'Y tế, dược phẩm, chăm sóc sức khỏe'],
            ['name' => 'Xây dựng', 'description' => 'Xây dựng, kiến trúc, bất động sản'],
            ['name' => 'Khác', 'description' => 'Các ngành nghề khác'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
