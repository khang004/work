@extends('layouts.candidate')

@section('title', 'Quản Lý CV')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Quản Lý CV</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-x-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($user->cv_path)
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> CV Hiện Tại</h6>
                            <p class="mb-2">
                                <strong>File:</strong> {{ basename($user->cv_path) }}<br>
                                <strong>Định dạng:</strong> {{ strtoupper(pathinfo($user->cv_path, PATHINFO_EXTENSION)) }}<br>
                                <strong>Upload lúc:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('candidate.cv.download') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-download"></i> Tải xuống
                                </a>
                                <form action="{{ route('candidate.cv.destroy') }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa CV này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Xóa CV
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Bạn chưa upload CV. Vui lòng upload CV để có thể ứng tuyển vào các công việc.
                        </div>
                    @endif

                    <hr>

                    <h6>{{ $user->cv_path ? 'Cập nhật CV mới' : 'Upload CV' }}</h6>
                    <form action="{{ route('candidate.cv.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="cv" class="form-label">Chọn file CV <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('cv') is-invalid @enderror" 
                                   id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                            @error('cv')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="bi bi-info-circle"></i> Định dạng: PDF, DOC, DOCX. Kích thước tối đa: 5MB
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('candidate.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload"></i> Upload CV
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($user->cv_path)
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-lightbulb"></i> Lưu ý</h6>
                        <ul class="small mb-0">
                            <li>CV của bạn sẽ được gửi kèm khi ứng tuyển vào các công việc</li>
                            <li>Nhà tuyển dụng có thể tải xuống và xem CV của bạn</li>
                            <li>Nên cập nhật CV định kỳ để thông tin luôn mới nhất</li>
                            <li>Khi upload CV mới, CV cũ sẽ bị thay thế</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
