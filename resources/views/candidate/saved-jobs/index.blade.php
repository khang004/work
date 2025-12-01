@extends('layouts.candidate')

@section('title', 'Việc Làm Đã Lưu')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-bookmark-heart"></i> Việc Làm Đã Lưu</h5>
            <a href="{{ route('candidate.dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
        <div class="card-body">
            @if($savedJobs->count() > 0)
                <div class="row">
                    @foreach($savedJobs as $job)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="text-decoration-none">
                                                {{ $job->title }}
                                            </a>
                                        </h6>
                                        <form action="{{ route('candidate.saved-jobs.destroy', $job->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    title="Bỏ lưu">
                                                <i class="bi bi-bookmark-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-2">
                                        @if($job->company->logo)
                                            <img src="{{ asset('storage/' . $job->company->logo) }}" 
                                                 alt="{{ $job->company->name }}" 
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <strong>{{ $job->company->name }}</strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt"></i> {{ $job->location->name }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge bg-secondary">{{ $job->category->name }}</span>
                                        @if($job->deadline < now())
                                            <span class="badge bg-danger">Đã hết hạn</span>
                                        @elseif($job->status === 'approved')
                                            <span class="badge bg-success">Đang tuyển</span>
                                        @endif
                                    </div>

                                    <p class="mb-2">
                                        <i class="bi bi-cash"></i> 
                                        <strong>{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ</strong>
                                    </p>

                                    <p class="mb-2 small">
                                        <i class="bi bi-people"></i> Số lượng: <strong>{{ $job->positions }} người</strong>
                                    </p>

                                    <p class="mb-2 small">
                                        <i class="bi bi-calendar"></i> Hạn nộp: 
                                        {{ \Carbon\Carbon::parse($job->deadline)->format('d/m/Y') }}
                                        @if($job->deadline < now())
                                            <span class="badge bg-danger ms-1">Hết hạn</span>
                                        @else
                                            <span class="badge bg-warning text-dark ms-1">{{ $job->deadline->diffInDays(now()) }}d</span>
                                        @endif
                                    </p>

                                    @if($job->skills->count() > 0)
                                        <div class="mb-2">
                                            @foreach($job->skills->take(3) as $skill)
                                                <span class="badge bg-light text-dark border">{{ $skill->name }}</span>
                                            @endforeach
                                            @if($job->skills->count() > 3)
                                                <span class="badge bg-light text-dark border">+{{ $job->skills->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $savedJobs->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bookmark" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Bạn chưa lưu công việc nào.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tìm việc ngay
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
