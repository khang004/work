<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // Công ty đăng tin
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Danh mục nghề nghiệp
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); // Địa điểm làm việc
            $table->string('title'); // Tiêu đề công việc
            $table->string('slug')->unique(); // Slug cho URL
            $table->text('description'); // Mô tả công việc
            $table->text('requirements'); // Yêu cầu công việc
            $table->decimal('salary_min', 10, 2)->nullable(); // Mức lương tối thiểu
            $table->decimal('salary_max', 10, 2)->nullable(); // Mức lương tối đa
            $table->date('deadline'); // Hạn nộp hồ sơ
            $table->enum('status', ['pending', 'approved', 'closed'])->default('pending'); // Trạng thái tin
            $table->boolean('is_featured')->default(false); // Việc làm nổi bật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
