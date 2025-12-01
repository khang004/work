@extends('layouts.employer')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Quản lý Tin tuyển dụng</h1>
        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Đăng tin mới
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tiêu đề</th>
                            <th>Danh mục</th>
                            <th>Địa điểm</th>
                            <th>SL Tuyển</th>
                            <th>Trạng thái</th>
                            <th>Hạn nộp</th>
                            <th>Ứng viên</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jobs as $job)
                        <tr>
                            <td>
                                <strong>{{ $job->title }}</strong>
                                @if($job->is_featured)
                                    <span class="badge bg-warning text-dark">Nổi bật</span>
                                @endif
                            </td>
                            <td>{{ $job->category->name }}</td>
                            <td>{{ $job->location->name }}</td>
                            <td><span class="badge bg-secondary">{{ $job->positions }}</span></td>
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
                                @elseif($job->deadline->diffInDays(now()) <= 3)
                                    <br><span class="badge bg-warning text-dark small">{{ $job->deadline->diffInDays(now()) }}d</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $job->applications->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('employer.jobs.edit', $job->id) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('employer.applications.index', $job->id) }}" 
                                       class="btn btn-sm btn-outline-info"
                                       title="Xem ứng viên">
                                        <i class="bi bi-people"></i>
                                    </a>
                                    @if($job->status == 'approved')
                                    <form action="{{ route('employer.jobs.close', $job->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Bạn có chắc muốn đóng tin này?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-warning"
                                                title="Đóng tin">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('employer.jobs.destroy', $job->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa tin này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Chưa có tin tuyển dụng nào. 
                                <a href="{{ route('employer.jobs.create') }}">Đăng tin ngay</a>
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
@endsection
