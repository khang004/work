@extends('layouts.employer')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3">Thông tin Công ty</h1>
        <p class="text-muted">Cập nhật thông tin công ty của bạn</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('employer.company.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên công ty <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $company->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo công ty</label>
                            @if($company->logo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $company->logo) }}" 
                                         alt="Logo" 
                                         class="img-thumbnail"
                                         style="max-width: 150px;">
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" 
                                   name="logo"
                                   accept="image/*">
                            <small class="form-text text-muted">Định dạng: JPG, PNG, GIF. Tối đa 2MB</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" 
                                   class="form-control @error('website') is-invalid @enderror" 
                                   id="website" 
                                   name="website" 
                                   value="{{ old('website', $company->website) }}"
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="size" class="form-label">Quy mô nhân sự</label>
                            <select class="form-select @error('size') is-invalid @enderror" 
                                    id="size" 
                                    name="size">
                                <option value="">Chọn quy mô...</option>
                                <option value="1-50" {{ old('size', $company->size) == '1-50' ? 'selected' : '' }}>1-50 nhân viên</option>
                                <option value="51-200" {{ old('size', $company->size) == '51-200' ? 'selected' : '' }}>51-200 nhân viên</option>
                                <option value="201-500" {{ old('size', $company->size) == '201-500' ? 'selected' : '' }}>201-500 nhân viên</option>
                                <option value="501-1000" {{ old('size', $company->size) == '501-1000' ? 'selected' : '' }}>501-1000 nhân viên</option>
                                <option value="1000+" {{ old('size', $company->size) == '1000+' ? 'selected' : '' }}>Trên 1000 nhân viên</option>
                            </select>
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="2"
                                      placeholder="Nhập địa chỉ công ty...">{{ old('address', $company->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả công ty</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="5"
                                      placeholder="Giới thiệu về công ty...">{{ old('description', $company->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Lưu thay đổi
                            </button>
                            <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Hướng dẫn</h5>
                    <ul class="small mb-0">
                        <li>Thông tin công ty sẽ hiển thị trên các tin tuyển dụng</li>
                        <li>Logo nên có kích thước vuông (500x500px)</li>
                        <li>Mô tả chi tiết giúp thu hút ứng viên</li>
                        <li>Website giúp ứng viên tìm hiểu thêm về công ty</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
