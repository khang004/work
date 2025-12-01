@extends('layouts.employer')

@section('title', 'Dashboard Nhà tuyển dụng')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Nhà tuyển dụng</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Tổng tin tuyển dụng</h6>
                <h2 class="mb-0">{{ $totalJobs }}</h2>
                <small class="text-muted">Tất cả tin đã đăng</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Đang hoạt động</h6>
                <h2 class="mb-0">{{ $activeJobs }}</h2>
                <small class="text-muted">Tin đang hiển thị</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Chờ duyệt</h6>
                <h2 class="mb-0">{{ $pendingJobs }}</h2>
                <small class="text-muted">Tin chờ admin duyệt</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="text-uppercase text-muted mb-2">Ứng viên</h6>
                <h2 class="mb-0">{{ $totalApplications }}</h2>
                <small class="text-muted">Tổng hồ sơ nhận được</small>
            </div>
        </div>
    </div>
</div>

<!-- Company Info -->
@if($company)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Thông tin công ty</h5>
                <a href="#" class="btn btn-sm btn-primary">Chỉnh sửa</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="img-fluid rounded">
                        @else
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                <i class="bi bi-building text-white fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-10">
                        <h4>{{ $company->name }}</h4>
                        @if($company->website)
                            <p class="mb-1"><i class="bi bi-globe"></i> <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></p>
                        @endif
                        @if($company->address)
                            <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $company->address }}</p>
                        @endif
                        @if($company->size)
                            <p class="mb-1"><i class="bi bi-people"></i> {{ $company->size }} nhân viên</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="#" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Đăng tin tuyển dụng mới</a>
                    <a href="#" class="btn btn-success"><i class="bi bi-briefcase"></i> Quản lý tin đăng</a>
                    <a href="#" class="btn btn-info"><i class="bi bi-people"></i> Xem ứng viên</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
