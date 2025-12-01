@extends('layouts.candidate')

@section('title', 'Dashboard Ứng viên')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Ứng viên</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Đã ứng tuyển</h6>
                <h2 class="mb-0">{{ $totalApplications }}</h2>
                <small class="text-muted">Tổng số việc đã ứng tuyển</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Đang chờ</h6>
                <h2 class="mb-0">{{ $pendingApplications }}</h2>
                <small class="text-muted">Hồ sơ đang chờ xét duyệt</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Phỏng vấn</h6>
                <h2 class="mb-0">{{ $interviewApplications }}</h2>
                <small class="text-muted">Được mời phỏng vấn</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Đã lưu</h6>
                <h2 class="mb-0">{{ $savedJobsCount }}</h2>
                <small class="text-muted">Việc làm đã lưu</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('candidate.profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-person"></i> Cập nhật hồ sơ
                    </a>
                    <a href="{{ route('candidate.cv.index') }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-arrow-up"></i> Quản lý CV
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-info">
                        <i class="bi bi-search"></i> Tìm việc làm
                    </a>
                    <a href="{{ route('candidate.saved-jobs.index') }}" class="btn btn-warning">
                        <i class="bi bi-bookmark"></i> Việc đã lưu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
@if($recentApplications->count() > 0)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Đơn ứng tuyển gần đây</h5>
        <a href="{{ route('candidate.applications.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Công việc</th>
                        <th>Công ty</th>
                        <th>Ngày nộp</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentApplications as $application)
                        <tr>
                            <td>
                                <a href="{{ route('jobs.show', $application->job->slug) }}" target="_blank">
                                    {{ $application->job->title }}
                                </a>
                            </td>
                            <td>{{ $application->job->company->name }}</td>
                            <td>{{ $application->created_at->format('d/m/Y') }}</td>
                            <td>
                                @switch($application->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Đang chờ</span>
                                        @break
                                    @case('reviewing')
                                        <span class="badge bg-info">Đang xem xét</span>
                                        @break
                                    @case('interview')
                                        <span class="badge bg-primary">Mời phỏng vấn</span>
                                        @break
                                    @case('hired')
                                        <span class="badge bg-success">Đã tuyển</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Từ chối</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
        <p class="text-muted mt-3">Bạn chưa ứng tuyển vào công việc nào.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="bi bi-search"></i> Tìm việc ngay
        </a>
    </div>
</div>
@endif
@endsection
