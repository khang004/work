@extends('layouts.employer')

@section('title', 'Chỉnh Sửa Tin Tuyển Dụng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Chỉnh Sửa Tin Tuyển Dụng</h1>
        <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('employer.jobs.update', $job->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Tiêu đề công việc <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $job->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="positions" class="form-label">Số lượng tuyển <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('positions') is-invalid @enderror" 
                                       id="positions" name="positions" value="{{ old('positions', $job->positions) }}" 
                                       min="1" max="999" required>
                                @error('positions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Số lượng người cần tuyển</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Chọn danh mục...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="location_id" class="form-label">Địa điểm <span class="text-danger">*</span></label>
                                <select class="form-select @error('location_id') is-invalid @enderror" 
                                        id="location_id" name="location_id" required>
                                    <option value="">Chọn địa điểm...</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" 
                                                {{ old('location_id', $job->location_id) == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="deadline" class="form-label">Hạn nộp hồ sơ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('deadline') is-invalid @enderror" 
                                       id="deadline" name="deadline" 
                                       value="{{ old('deadline', $job->deadline->format('Y-m-d')) }}" required>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="salary_min" class="form-label">Lương tối thiểu (VNĐ)</label>
                                <input type="number" class="form-control @error('salary_min') is-invalid @enderror" 
                                       id="salary_min" name="salary_min" 
                                       value="{{ old('salary_min', $job->salary_min) }}" 
                                       min="0" step="100000">
                                @error('salary_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="salary_max" class="form-label">Lương tối đa (VNĐ)</label>
                                <input type="number" class="form-control @error('salary_max') is-invalid @enderror" 
                                       id="salary_max" name="salary_max" 
                                       value="{{ old('salary_max', $job->salary_max) }}" 
                                       min="0" step="100000">
                                @error('salary_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả công việc <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="6" required>{{ old('description', $job->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Yêu cầu công việc <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                      id="requirements" name="requirements" rows="6" required>{{ old('requirements', $job->requirements) }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kỹ năng yêu cầu</label>
                            <div class="row">
                                @foreach($skills->chunk(4) as $chunk)
                                    @foreach($chunk as $skill)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="skills[]" value="{{ $skill->id }}" 
                                                       id="skill{{ $skill->id }}"
                                                       {{ in_array($skill->id, old('skills', $job->skills->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="skill{{ $skill->id }}">
                                                    {{ $skill->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" 
                                       id="is_featured" value="1" 
                                       {{ old('is_featured', $job->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    Đánh dấu là việc làm nổi bật
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Lưu ý:</strong> Sau khi chỉnh sửa, tin tuyển dụng sẽ cần được admin phê duyệt lại.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">
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
</div>
@endsection
