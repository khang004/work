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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'employer', 'candidate'])->default('candidate')->after('email'); // Vai trò người dùng
            $table->string('phone')->nullable()->after('role'); // Số điện thoại
            $table->text('address')->nullable()->after('phone'); // Địa chỉ
            $table->text('bio')->nullable()->after('address'); // Giới thiệu bản thân (cho ứng viên)
            $table->string('cv_path')->nullable()->after('bio'); // Đường dẫn CV (cho ứng viên)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'bio', 'cv_path']);
        });
    }
};
