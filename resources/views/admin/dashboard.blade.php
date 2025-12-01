@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-2">Tổng người dùng</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
                <small>Ứng viên: {{ $totalCandidates }} | NTD: {{ $totalEmployers }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-2">Việc làm</h6>
                        <h2 class="mb-0">{{ $totalJobs }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-briefcase fs-1"></i>
                    </div>
                </div>
                <small>Đang hoạt động: {{ $activeJobs }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-2">Chờ duyệt</h6>
                        <h2 class="mb-0">{{ $pendingJobs + $pendingEmployers }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-hourglass-split fs-1"></i>
                    </div>
                </div>
                <small>Tin: {{ $pendingJobs }} | NTD: {{ $pendingEmployers }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-2">Đơn ứng tuyển</h6>
                        <h2 class="mb-0">{{ $totalApplications }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-file-earmark-text fs-1"></i>
                    </div>
                </div>
                <small>Công ty: {{ $totalCompanies }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Pending Employers -->
@if($pendingEmployersList->count() > 0)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Nhà tuyển dụng chờ duyệt</h5>
        <a href="{{ route('admin.users.pending') }}" class="btn btn-sm btn-primary">Xem tất cả</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingEmployersList as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.users.approve', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận duyệt?')">Duyệt</button>
                            </form>
                            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận từ chối?')">Từ chối</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Pending Jobs -->
@if($pendingJobsList->count() > 0)
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tin tuyển dụng chờ duyệt</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Công ty</th>
                        <th>Danh mục</th>
                        <th>Ngày đăng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingJobsList as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->company->name }}</td>
                        <td><span class="badge bg-secondary">{{ $job->category->name }}</span></td>
                        <td>{{ $job->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-success">Duyệt</button>
                            <button class="btn btn-sm btn-danger">Từ chối</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
