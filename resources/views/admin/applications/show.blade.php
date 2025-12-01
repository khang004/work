@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Ứng Tuyển')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-file-earmark-text"></i> Chi Tiết Đơn Ứng Tuyển</h1>
    <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Thông tin ứng viên -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle"></i> Thông Tin Ứng Viên</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th width="35%">Họ tên:</th>
                        <td><strong>{{ $application->user->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $application->user->email }}</td>
                    </tr>
                    <tr>
                        <th>Điện thoại:</th>
                        <td>{{ $application->user->phone ?? 'Chưa cập nhật' }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ:</th>
                        <td>{{ $application->user->address ?? 'Chưa cập nhật' }}</td>
                    </tr>
                </table>

                @if($application->user->bio)
                    <hr>
                    <h6><i class="bi bi-file-text"></i> Giới thiệu bản thân:</h6>
                    <p class="small">{{ $application->user->bio }}</p>
                @endif

                @if($application->user->skills->count() > 0)
                    <hr>
                    <h6><i class="bi bi-gear"></i> Kỹ năng:</h6>
                    <div>
                        @foreach($application->user->skills as $skill)
                            <span class="badge bg-secondary me-1 mb-1">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Thông tin công việc -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Thông Tin Công Việc</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th width="35%">Vị trí:</th>
                        <td>
                            <a href="{{ route('jobs.show', $application->job->slug) }}" target="_blank">
                                <strong>{{ $application->job->title }}</strong>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Công ty:</th>
                        <td>{{ $application->job->company->name }}</td>
                    </tr>
                    <tr>
                        <th>Danh mục:</th>
                        <td>
                            <span class="badge bg-primary">{{ $application->job->category->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Địa điểm:</th>
                        <td>{{ $application->job->location->name }}</td>
                    </tr>
                    <tr>
                        <th>Số lượng:</th>
                        <td><span class="badge bg-secondary">{{ $application->job->positions }} người</span></td>
                    </tr>
                    <tr>
                        <th>Mức lương:</th>
                        <td>
                            @if($application->job->salary_min && $application->job->salary_max)
                                {{ number_format($application->job->salary_min) }} - {{ number_format($application->job->salary_max) }} VNĐ
                            @else
                                Thỏa thuận
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Hạn nộp:</th>
                        <td>{{ $application->job->deadline->format('d/m/Y') }}</td>
                    </tr>
                </table>

                @if($application->job->skills->count() > 0)
                    <hr>
                    <h6><i class="bi bi-gear"></i> Kỹ năng yêu cầu:</h6>
                    <div>
                        @foreach($application->job->skills as $skill)
                            <span class="badge bg-info me-1 mb-1">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Thông tin đơn ứng tuyển -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-file-earmark-check"></i> Thông Tin Đơn Ứng Tuyển</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Ngày nộp:</strong><br>
                        {{ $application->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Trạng thái hiện tại:</strong><br>
                        @switch($application->status)
                            @case('pending')
                                <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                @break
                            @case('reviewing')
                                <span class="badge bg-info">Đang xem xét</span>
                                @break
                            @case('interview')
                                <span class="badge bg-primary">Mời phỏng vấn</span>
                                @break
                            @case('hired')
                                <span class="badge bg-success">Đã tuyển dụng</span>
                                @break
                            @case('rejected')
                                <span class="badge bg-danger">Đã từ chối</span>
                                @break
                        @endswitch
                    </div>
                    <div class="col-md-4">
                        <strong>CV đính kèm:</strong><br>
                        @if($application->cv_path)
                            <a href="{{ asset('storage/' . $application->cv_path) }}" 
                               target="_blank" 
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-download"></i> Tải CV
                            </a>
                        @else
                            <span class="text-muted">Không có</span>
                        @endif
                    </div>
                </div>

                @if($application->cover_letter)
                    <hr>
                    <h6><i class="bi bi-envelope"></i> Thư giới thiệu:</h6>
                    <div class="bg-light p-3 rounded">
                        {!! nl2br(e($application->cover_letter)) !!}
                    </div>
                @endif

                @if($application->note)
                    <hr>
                    <h6><i class="bi bi-sticky"></i> Ghi chú:</h6>
                    <div class="alert alert-secondary">
                        {{ $application->note }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cập nhật trạng thái -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Cập Nhật Trạng Thái</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.applications.update-status', $application->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>
                                    Chờ xử lý
                                </option>
                                <option value="reviewing" {{ $application->status == 'reviewing' ? 'selected' : '' }}>
                                    Đang xem xét
                                </option>
                                <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>
                                    Mời phỏng vấn
                                </option>
                                <option value="hired" {{ $application->status == 'hired' ? 'selected' : '' }}>
                                    Đã tuyển dụng
                                </option>
                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>
                                    Từ chối
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="3" 
                                      placeholder="Thêm ghi chú về đơn ứng tuyển...">{{ old('note', $application->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
