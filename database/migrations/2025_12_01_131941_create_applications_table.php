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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ứng viên
            $table->foreignId('job_id')->constrained()->onDelete('cascade'); // Công việc
            $table->text('cover_letter')->nullable(); // Thư giới thiệu
            $table->string('cv_path')->nullable(); // Đường dẫn CV
            $table->enum('status', ['pending', 'reviewing', 'interview', 'hired', 'rejected'])->default('pending'); // Trạng thái
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
