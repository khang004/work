@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Quản lý Việc làm</h1>
        <a href="{{ route('admin.jobs.pending') }}" class="btn btn-warning">
            <i class="bi bi-clock"></i> Việc chờ duyệt
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Thống kê -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Tổng việc làm</h5>
                    <h2>{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Chờ duyệt</h5>
                    <h2>{{ $stats['pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Đã duyệt</h5>
                    <h2>{{ $stats['approved'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">Đã đóng</h5>
                    <h2>{{ $stats['closed'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jobs.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           placeholder="Tìm kiếm việc làm..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Đã đóng</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tìm
                    </button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách việc làm -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tiêu đề</th>
                            <th>Công ty</th>
                            <th>Danh mục</th>
                            <th>SL Tuyển</th>
                            <th>Lương</th>
                            <th>Trạng thái</th>
                            <th>Hạn nộp</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                        <tr>
                            <td>{{ $loop->iteration + ($jobs->currentPage() - 1) * $jobs->perPage() }}</td>
                            <td>
                                <strong>{{ $job->title }}</strong><br>
                                <small class="text-muted">{{ $job->location->name }}</small>
                            </td>
                            <td>{{ $job->company->name }}</td>
                            <td><span class="badge bg-info">{{ $job->category->name }}</span></td>
                            <td><span class="badge bg-secondary">{{ $job->positions }}</span></td>
                            <td>
                                @if($job->salary_min && $job->salary_max)
                                    {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ
                                @else
                                    Thỏa thuận
                                @endif
                            </td>
                            <td>
                                @if($job->status == 'pending')
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                @elseif($job->status == 'approved')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @elseif($job->status == 'closed')
                                    <span class="badge bg-secondary">Đã đóng</span>
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($job->deadline)->format('d/m/Y') }}
                                @if($job->deadline < now())
                                    <br><span class="badge bg-danger small">Hết hạn</span>
                                @elseif($job->deadline->diffInDays(now()) <= 7)
                                    <br><span class="badge bg-warning text-dark small">{{ $job->deadline->diffInDays(now()) }}d</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.jobs.show', $job->id) }}" 
                                       class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteJob({{ $job->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $job->id }}" 
                                      action="{{ route('admin.jobs.destroy', $job->id) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Chưa có việc làm nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function deleteJob(id) {
    if (confirm('Bạn có chắc chắn muốn xóa việc làm này?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
