@extends('layouts.candidate')

@section('title', 'Việc Đã Ứng Tuyển')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-briefcase"></i> Việc Làm Đã Ứng Tuyển</h5>
            <a href="{{ route('candidate.dashboard') }}" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left"></i> Dashboard
            </a>
        </div>
        <div class="card-body">
            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Công việc</th>
                                <th>Công ty</th>
                                <th>Ngày ứng tuyển</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    <td>
                                        <a href="{{ route('jobs.show', $application->job->slug) }}" target="_blank">
                                            <strong>{{ $application->job->title }}</strong>
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt"></i> {{ $application->job->location->name }}
                                            | <i class="bi bi-tag"></i> {{ $application->job->category->name }}
                                        </small>
                                    </td>
                                    <td>{{ $application->job->company->name }}</td>
                                    <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @switch($application->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock"></i> Đang chờ
                                                </span>
                                                @break
                                            @case('reviewing')
                                                <span class="badge bg-info">
                                                    <i class="bi bi-eye"></i> Đang xem xét
                                                </span>
                                                @break
                                            @case('interview')
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-people"></i> Mời phỏng vấn
                                                </span>
                                                @break
                                            @case('hired')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> Đã tuyển
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> Từ chối
                                                </span>
                                                @break
                                        @endswitch
                                        @if($application->note)
                                            <br>
                                            <small class="badge bg-secondary mt-1">
                                                <i class="bi bi-chat-left-text"></i> Có phản hồi
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('candidate.applications.show', $application->id) }}" 
                                           class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($application->status === 'pending')
                                            <form action="{{ route('candidate.applications.cancel', $application->id) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc muốn hủy đơn ứng tuyển này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hủy ứng tuyển">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $applications->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Bạn chưa ứng tuyển vào công việc nào.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tìm việc ngay
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
