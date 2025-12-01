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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Nhà tuyển dụng sở hữu công ty
            $table->string('name'); // Tên công ty
            $table->string('logo')->nullable(); // Logo công ty
            $table->string('website')->nullable(); // Website công ty
            $table->integer('size')->nullable(); // Quy mô nhân sự
            $table->text('address')->nullable(); // Địa chỉ
            $table->text('description')->nullable(); // Mô tả công ty
            $table->string('map_embed')->nullable(); // Google Maps embed code
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
