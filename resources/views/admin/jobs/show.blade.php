@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Việc làm</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $job->title }}</h5>
                    @if($job->status == 'pending')
                        <span class="badge bg-warning">Chờ duyệt</span>
                    @elseif($job->status == 'approved')
                        <span class="badge bg-success">Đã duyệt</span>
                    @elseif($job->status == 'closed')
                        <span class="badge bg-secondary">Đã đóng</span>
                    @endif
                </div>
                <div class="card-body">
                    <h6>Mô tả công việc</h6>
                    <div class="mb-4">{!! nl2br(e($job->description)) !!}</div>

                    <h6>Yêu cầu</h6>
                    <div class="mb-4">{!! nl2br(e($job->requirements)) !!}</div>

                    @if($job->skills->count() > 0)
                    <h6>Kỹ năng yêu cầu</h6>
                    <div class="mb-3">
                        @foreach($job->skills as $skill)
                            <span class="badge bg-secondary me-1">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Thông tin công ty -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Thông tin công ty</h6>
                    <hr>
                    <p><strong>Tên công ty:</strong><br>{{ $job->company->name }}</p>
                    <p><strong>Website:</strong><br>
                        @if($job->company->website)
                            <a href="{{ $job->company->website }}" target="_blank">{{ $job->company->website }}</a>
                        @else
                            <span class="text-muted">Chưa cập nhật</span>
                        @endif
                    </p>
                    <p><strong>Địa chỉ:</strong><br>{{ $job->company->address ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>

            <!-- Thông tin việc làm -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="card-title">Thông tin việc làm</h6>
                    <hr>
                    <p><strong>Danh mục:</strong><br>{{ $job->category->name }}</p>
                    <p><strong>Địa điểm:</strong><br>{{ $job->location->name }}</p>
                    <p><strong>Mức lương:</strong><br>
                        @if($job->salary_min && $job->salary_max)
                            {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ
                        @else
                            Thỏa thuận
                        @endif
                    </p>
                    <p><strong>Hạn nộp:</strong><br>{{ \Carbon\Carbon::parse($job->deadline)->format('d/m/Y') }}</p>
                    <p><strong>Ngày đăng:</strong><br>{{ \Carbon\Carbon::parse($job->created_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Thao tác -->
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Thao tác</h6>
                    <hr>
                    
                    @if($job->status == 'pending')
                        <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Duyệt việc làm
                            </button>
                        </form>
                        <form action="{{ route('admin.jobs.reject', $job->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn từ chối?')">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-circle"></i> Từ chối
                            </button>
                        </form>
                    @elseif($job->status == 'approved')
                        <form action="{{ route('admin.jobs.close', $job->id) }}" method="POST" class="mb-2" onsubmit="return confirm('Bạn có chắc chắn muốn đóng việc làm này?')">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-lock"></i> Đóng việc làm
                            </button>
                        </form>
                    @endif

                    <hr>

                    <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa việc làm này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Xóa việc làm
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
