@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Việc làm chờ duyệt</h1>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                    <form action="{{ route('admin.jobs.approve', $job->id) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-circle"></i> Duyệt
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.jobs.reject', $job->id) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn từ chối việc làm này?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-circle"></i> Từ chối
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-check-circle" style="font-size: 48px;"></i>
                                <p class="mt-3">Không có việc làm nào chờ duyệt</p>
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
