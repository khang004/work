@extends('layouts.candidate')

@section('title', 'Chi Tiết Đơn Ứng Tuyển')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Chi Tiết Đơn Ứng Tuyển</h5>
                    <a href="{{ route('candidate.applications.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong>
                            @switch($application->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Đang chờ xử lý
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
                                        <i class="bi bi-check-circle"></i> Đã tuyển dụng
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Đã từ chối
                                    </span>
                                    @break
                            @endswitch
                        </div>
                        <div class="col-md-6 text-end">
                            <strong>Ngày ứng tuyển:</strong> {{ $application->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <hr>

                    <h6><i class="bi bi-briefcase"></i> Thông Tin Công Việc</h6>
                    <table class="table table-sm">
                        <tr>
                            <th width="30%">Vị trí:</th>
                            <td>
                                <a href="{{ route('jobs.show', $application->job->slug) }}" target="_blank">
                                    {{ $application->job->title }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Công ty:</th>
                            <td>{{ $application->job->company->name }}</td>
                        </tr>
                        <tr>
                            <th>Danh mục:</th>
                            <td>{{ $application->job->category->name }}</td>
                        </tr>
                        <tr>
                            <th>Địa điểm:</th>
                            <td>{{ $application->job->location->name }}</td>
                        </tr>
                        <tr>
                            <th>Mức lương:</th>
                            <td>
                                {{ number_format($application->job->salary_min) }} - {{ number_format($application->job->salary_max) }} VNĐ
                            </td>
                        </tr>
                        <tr>
                            <th>Hạn nộp:</th>
                            <td>
                                {{ \Carbon\Carbon::parse($application->job->deadline)->format('d/m/Y') }}
                                @if($application->job->deadline < now())
                                    <span class="badge bg-secondary">Đã hết hạn</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <hr>

                    <h6><i class="bi bi-file-earmark-pdf"></i> CV Đã Nộp</h6>
                    <p>
                        <strong>File:</strong> {{ basename($application->cv_path) }}<br>
                        <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-download"></i> Xem CV
                        </a>
                    </p>

                    @if($application->cover_letter)
                        <hr>
                        <h6><i class="bi bi-envelope"></i> Thư Giới Thiệu</h6>
                        <div class="border p-3 bg-light rounded">
                            {!! nl2br(e($application->cover_letter)) !!}
                        </div>
                    @endif

                    @if($application->note)
                        <hr>
                        <div class="alert alert-info">
                            <h6><i class="bi bi-sticky"></i> Phản Hồi Từ Nhà Tuyển Dụng</h6>
                            <p class="mb-0">{!! nl2br(e($application->note)) !!}</p>
                        </div>
                    @endif

                    @if($application->status === 'pending')
                        <hr>
                        <form action="{{ route('candidate.applications.cancel', $application->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Bạn có chắc muốn hủy đơn ứng tuyển này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hủy đơn ứng tuyển
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
