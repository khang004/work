<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\JobController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Candidate\DashboardController as CandidateDashboardController;
use App\Http\Controllers\Employer\DashboardController as EmployerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;
use App\Http\Controllers\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;

// ========== GUEST ROUTES (Khách) ==========
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs/search', [JobController::class, 'search'])->name('jobs.search');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');

// ========== AUTH ROUTES (Đăng nhập/Đăng ký) ==========
// Auth cho User (Candidate + Employer)
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Auth cho Admin
Route::middleware(['admin.session', 'guest:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('login.post');
});

// Logout routes
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:web');
Route::post('/admin/logout', [LoginController::class, 'adminLogout'])->name('admin.logout')->middleware(['admin.session', 'auth:admin']);

// ========== CANDIDATE ROUTES (Ứng viên) ==========
Route::middleware(['auth:web', 'role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý hồ sơ
    Route::get('/profile', [\App\Http\Controllers\Candidate\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Candidate\ProfileController::class, 'update'])->name('profile.update');
    
    // Quản lý CV
    Route::get('/cv', [\App\Http\Controllers\Candidate\CVController::class, 'index'])->name('cv.index');
    Route::post('/cv/upload', [\App\Http\Controllers\Candidate\CVController::class, 'upload'])->name('cv.upload');
    Route::delete('/cv', [\App\Http\Controllers\Candidate\CVController::class, 'destroy'])->name('cv.destroy');
    Route::get('/cv/download', [\App\Http\Controllers\Candidate\CVController::class, 'download'])->name('cv.download');
    
    // Quản lý đơn ứng tuyển
    Route::get('/applications', [\App\Http\Controllers\Candidate\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [\App\Http\Controllers\Candidate\ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/jobs/{jobId}/apply', [\App\Http\Controllers\Candidate\ApplicationController::class, 'apply'])->name('jobs.apply');
    Route::delete('/applications/{id}', [\App\Http\Controllers\Candidate\ApplicationController::class, 'cancel'])->name('applications.cancel');
    
    // Quản lý việc làm đã lưu
    Route::get('/saved-jobs', [\App\Http\Controllers\Candidate\SavedJobController::class, 'index'])->name('saved-jobs.index');
    Route::post('/jobs/{jobId}/save', [\App\Http\Controllers\Candidate\SavedJobController::class, 'store'])->name('saved-jobs.store');
    Route::delete('/saved-jobs/{jobId}', [\App\Http\Controllers\Candidate\SavedJobController::class, 'destroy'])->name('saved-jobs.destroy');
});

// ========== EMPLOYER ROUTES (Nhà tuyển dụng) ==========
Route::middleware(['auth:web', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý công ty
    Route::get('/company', [\App\Http\Controllers\Employer\CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company', [\App\Http\Controllers\Employer\CompanyController::class, 'update'])->name('company.update');
    
    // Quản lý tin tuyển dụng
    Route::resource('jobs', \App\Http\Controllers\Employer\JobController::class)->except(['show']);
    Route::patch('/jobs/{id}/close', [\App\Http\Controllers\Employer\JobController::class, 'close'])->name('jobs.close');
    
    // Quản lý ứng viên
    Route::get('/jobs/{job}/applications', [\App\Http\Controllers\Employer\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [\App\Http\Controllers\Employer\ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/status', [\App\Http\Controllers\Employer\ApplicationController::class, 'updateStatus'])->name('applications.status');
});

// ========== ADMIN ROUTES (Quản trị viên) ==========
Route::middleware(['admin.session', 'auth:admin', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý người dùng
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/pending-employers', [AdminUserController::class, 'pendingEmployers'])->name('users.pending');
    Route::post('/users/{id}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{id}/reject', [AdminUserController::class, 'reject'])->name('users.reject');
    Route::post('/users/{id}/toggle-approval', [AdminUserController::class, 'toggleApproval'])->name('users.toggle');
    
    // Quản lý Danh mục
    Route::resource('categories', AdminCategoryController::class);
    
    // Quản lý Địa điểm
    Route::resource('locations', AdminLocationController::class);
    
    // Quản lý Kỹ năng
    Route::resource('skills', AdminSkillController::class);
    
    // Quản lý Việc làm
    Route::get('/jobs/pending', [AdminJobController::class, 'pending'])->name('jobs.pending');
    Route::post('/jobs/{id}/approve', [AdminJobController::class, 'approve'])->name('jobs.approve');
    Route::post('/jobs/{id}/reject', [AdminJobController::class, 'reject'])->name('jobs.reject');
    Route::post('/jobs/{id}/close', [AdminJobController::class, 'close'])->name('jobs.close');
    Route::resource('jobs', AdminJobController::class)->except(['create', 'store']);
    
    // Quản lý Đơn ứng tuyển
    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{id}/status', [AdminApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::delete('/applications/{id}', [AdminApplicationController::class, 'destroy'])->name('applications.destroy');
});
