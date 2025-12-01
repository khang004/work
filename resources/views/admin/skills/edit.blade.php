@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3">Sửa Kỹ năng</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.skills.index') }}">Kỹ năng</a></li>
                <li class="breadcrumb-item active">Sửa</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.skills.update', $skill->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên kỹ năng <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $skill->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug hiện tại</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $skill->slug }}"
                                   disabled>
                            <small class="form-text text-muted">
                                Slug sẽ được cập nhật tự động khi thay đổi tên
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thống kê</label>
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle"></i>
                                Kỹ năng này được yêu cầu trong <strong>{{ $skill->jobs()->count() }}</strong> việc làm
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Cập nhật
                            </button>
                            <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Lưu ý</h5>
                    <ul class="small mb-0">
                        <li>Không thể xóa kỹ năng đang được sử dụng</li>
                        <li>Thay đổi tên sẽ tự động cập nhật slug</li>
                        <li>Tên kỹ năng phải là duy nhất</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
