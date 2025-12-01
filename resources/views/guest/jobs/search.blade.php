@extends('layouts.guest')

@section('title', 'Tìm kiếm việc làm')

@section('content')
<div class="container">
    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('jobs.search') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Từ khóa</label>
                        <input type="text" 
                               class="form-control" 
                               name="keyword" 
                               placeholder="Nhập từ khóa..." 
                               value="{{ request('keyword') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Danh mục</label>
                        <select class="form-select" name="category_id">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Địa điểm</label>
                        <select class="form-select" name="location_id">
                            <option value="">Tất cả địa điểm</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-2">Kết quả tìm kiếm</h4>
            @if(request('keyword') || request('category_id') || request('location_id'))
                <div class="d-flex gap-2 flex-wrap">
                    @if(request('keyword'))
                        <span class="badge bg-primary">
                            <i class="bi bi-search"></i> {{ request('keyword') }}
                        </span>
                    @endif
                    @if(request('category_id'))
                        @php
                            $selectedCategory = $categories->find(request('category_id'));
                        @endphp
                        @if($selectedCategory)
                            <span class="badge bg-info text-dark">
                                <i class="bi bi-tag"></i> {{ $selectedCategory->name }}
                            </span>
                        @endif
                    @endif
                    @if(request('location_id'))
                        @php
                            $selectedLocation = $locations->find(request('location_id'));
                        @endphp
                        @if($selectedLocation)
                            <span class="badge bg-success">
                                <i class="bi bi-geo-alt"></i> {{ $selectedLocation->name }}
                            </span>
                        @endif
                    @endif
                    <a href="{{ route('jobs.search') }}" class="badge bg-secondary text-decoration-none">
                        <i class="bi bi-x-circle"></i> Xóa bộ lọc
                    </a>
                </div>
            @endif
        </div>
        <span class="text-muted">Tìm thấy {{ $jobs->total() }} việc làm</span>
    </div>

    @if($jobs->count() > 0)
        <div class="row">
            @foreach($jobs as $job)
                <div class="col-md-12 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    @if($job->company->logo)
                                        <img src="{{ asset('storage/' . $job->company->logo) }}" 
                                             alt="{{ $job->company->name }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 80px;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 80px; height: 80px;">
                                            <i class="bi bi-building text-white fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <h5 class="card-title mb-2">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none">
                                            {{ $job->title }}
                                        </a>
                                        @if($job->is_featured)
                                            <span class="badge bg-warning text-dark">Nổi bật</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-building"></i> {{ $job->company->name }}
                                    </p>
                                    <div class="mb-2">
                                        <span class="badge bg-primary me-1">{{ $job->category->name }}</span>
                                        <span class="badge bg-info text-dark me-1">
                                            <i class="bi bi-geo-alt"></i> {{ $job->location->name }}
                                        </span>
                                        <span class="badge bg-secondary me-1">
                                            <i class="bi bi-people"></i> {{ $job->positions }} người
                                        </span>
                                        @if($job->salary_min && $job->salary_max)
                                            <span class="badge bg-success">
                                                <i class="bi bi-currency-dollar"></i> {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-clock"></i> Hạn nộp: {{ $job->deadline->format('d/m/Y') }}
                                        @if($job->deadline < now())
                                            <span class="badge bg-danger ms-1">Đã hết hạn</span>
                                        @else
                                            <span class="badge bg-warning text-dark ms-1">Còn {{ $job->deadline->diffInDays(now()) }} ngày</span>
                                        @endif
                                        <span class="ms-3"><i class="bi bi-calendar"></i> Đăng: {{ $job->created_at->format('d/m/Y') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $jobs->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
            <h5>Không tìm thấy việc làm phù hợp</h5>
            <p class="mb-0">Hãy thử tìm kiếm với từ khóa khác hoặc mở rộng bộ lọc</p>
        </div>
    @endif
</div>
@endsection
