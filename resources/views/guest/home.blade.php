@extends('layouts.guest')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Tìm công việc mơ ước của bạn</h1>
                <p class="lead mb-4">Hàng nghìn cơ hội việc làm đang chờ đón bạn</p>
            </div>
            <div class="col-lg-6">
                <!-- Search Form -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('jobs.search') }}" method="GET">
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg" name="keyword" 
                                       placeholder="Tìm theo vị trí, công ty..." 
                                       value="{{ request('keyword') }}">
                            </div>
                            <div class="mb-3">
                                <select class="form-select" name="category_id">
                                    <option value="">Tất cả ngành nghề</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <select class="form-select" name="location_id">
                                    <option value="">Tất cả địa điểm</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-search"></i> Tìm kiếm ngay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
@if($featuredJobs->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Việc làm nổi bật</h2>
        <div class="row">
            @foreach($featuredJobs as $job)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            @if($job->company->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}" alt="{{ $job->company->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-building text-white"></i>
                                </div>
                            @endif
                            <div class="ms-3">
                                <h5 class="card-title mb-1">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none">{{ $job->title }}</a>
                                </h5>
                                <p class="text-muted mb-0">{{ $job->company->name }}</p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $job->category->name }}</span>
                            <span class="badge bg-info text-dark">{{ $job->location->name }}</span>
                        </div>
                        @if($job->salary_min && $job->salary_max)
                        <p class="text-success mb-2">
                            <i class="bi bi-currency-dollar"></i> {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ
                        </p>
                        @endif
                        <p class="mb-2">
                            <i class="bi bi-people"></i> <strong>Số lượng:</strong> {{ $job->positions }} người
                        </p>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-clock"></i> Hạn nộp: {{ $job->deadline->format('d/m/Y') }}
                            @if($job->deadline < now())
                                <span class="badge bg-danger ms-1">Đã hết hạn</span>
                            @else
                                <span class="badge bg-warning text-dark ms-1">Còn {{ $job->deadline->diffInDays(now()) }} ngày</span>
                            @endif
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-calendar-plus"></i> Đăng: {{ $job->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Jobs -->
@if($latestJobs->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Việc làm mới nhất</h2>
        <div class="row">
            @foreach($latestJobs as $job)
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">
                            <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none">{{ Str::limit($job->title, 50) }}</a>
                        </h6>
                        <p class="text-muted small mb-2">{{ $job->company->name }}</p>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $job->location->name }}
                        </p>
                        <p class="small mb-2">
                            <i class="bi bi-people"></i> <strong>{{ $job->positions }}</strong> người
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-clock"></i> Hạn: {{ $job->deadline->format('d/m/Y') }}
                            @if($job->deadline >= now())
                                <span class="badge bg-warning text-dark">{{ $job->deadline->diffInDays(now()) }}d</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('jobs.search') }}" class="btn btn-primary">Xem tất cả việc làm</a>
        </div>
    </div>
</section>
@endif

<!-- Top Companies -->
@if($topCompanies->count() > 0)
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Công ty hàng đầu</h2>
        <div class="row">
            @foreach($topCompanies as $company)
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="rounded mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-building text-white fs-2"></i>
                            </div>
                        @endif
                        <h6 class="card-title">{{ $company->name }}</h6>
                        <p class="text-muted small mb-0">{{ $company->jobs_count }} việc làm</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Browse by Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4 text-center">Tìm việc theo ngành nghề</h2>
        <div class="row">
            @foreach($categories->take(8) as $category)
                <div class="col-md-6 col-lg-3 mb-3">
                    <a href="{{ route('jobs.search', ['category_id' => $category->id]) }}" 
                       class="card text-center text-decoration-none hover-shadow h-100">
                        <div class="card-body">
                            <i class="bi bi-briefcase-fill fs-1 text-primary mb-3"></i>
                            <h6 class="card-title">{{ $category->name }}</h6>
                            <p class="text-muted small mb-0">
                                {{ $category->jobs()->where('status', 'approved')->where('deadline', '>=', now())->count() }} việc làm
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('jobs.search') }}" class="btn btn-outline-primary">
                Xem tất cả ngành nghề <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endsection

<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    transform: translateY(-3px);
}
</style>
