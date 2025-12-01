@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Ứng Tuyển')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-file-earmark-text"></i> Quản Lý Đơn Ứng Tuyển</h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h3 class="mb-0">{{ $totalApplications }}</h3>
                <small class="text-muted">Tổng đơn</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card text-center border-warning">
            <div class="card-body">
                <h3 class="mb-0 text-warning">{{ $pendingCount }}</h3>
                <small class="text-muted">Chờ xử lý</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card text-center border-info">
            <div class="card-body">
                <h3 class="mb-0 text-info">{{ $reviewingCount }}</h3>
                <small class="text-muted">Đang xét</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card text-center border-primary">
            <div class="card-body">
                <h3 class="mb-0 text-primary">{{ $interviewCount }}</h3>
                <small class="text-muted">Phỏng vấn</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card text-center border-success">
            <div class="card-body">
                <h3 class="mb-0 text-success">{{ $hiredCount }}</h3>
                <small class="text-muted">Đã tuyển</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card text-center border-danger">
            <div class="card-body">
                <h3 class="mb-0 text-danger">{{ $rejectedCount }}</h3>
                <small class="text-muted">Từ chối</small>
            </div>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('admin.applications.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tìm ứng viên</label>
                    <input type="text" class="form-control" name="keyword" 
                           placeholder="Tên hoặc email..." value="{{ request('keyword') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Đang xem xét</option>
                        <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Phỏng vấn</option>
                        <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Đã tuyển</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Applications Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Danh sách đơn ứng tuyển</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Ứng viên</th>
                        <th>Công việc</th>
                        <th>Công ty</th>
                        <th>Ngày nộp</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr>
                        <td>{{ $loop->iteration + ($applications->currentPage() - 1) * $applications->perPage() }}</td>
                        <td>
                            <strong>{{ $application->user->name }}</strong><br>
                            <small class="text-muted">{{ $application->user->email }}</small>
                        </td>
                        <td>
                            <a href="{{ route('jobs.show', $application->job->slug) }}" target="_blank">
                                {{ Str::limit($application->job->title, 40) }}
                            </a><br>
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> {{ $application->job->category->name }}
                            </small>
                        </td>
                        <td>{{ $application->job->company->name }}</td>
                        <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @switch($application->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                    @break
                                @case('reviewing')
                                    <span class="badge bg-info">Đang xem xét</span>
                                    @break
                                @case('interview')
                                    <span class="badge bg-primary">Phỏng vấn</span>
                                    @break
                                @case('hired')
                                    <span class="badge bg-success">Đã tuyển</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">Từ chối</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.applications.show', $application->id) }}" 
                                   class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $application->id }}"
                                        title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $application->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Xác nhận xóa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Bạn có chắc muốn xóa đơn ứng tuyển của <strong>{{ $application->user->name }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Không có đơn ứng tuyển nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $applications->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
