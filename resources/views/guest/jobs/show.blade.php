@extends('layouts.guest')

@section('title', $job->title)

@section('content')
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <!-- Job Header -->
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            @if($job->company->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}" 
                                     alt="{{ $job->company->name }}" 
                                     class="rounded" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                     style="width: 100px; height: 100px;">
                                    <i class="bi bi-building text-white fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h2 class="mb-2">{{ $job->title }}</h2>
                            <h5 class="text-muted mb-3">
                                <i class="bi bi-building"></i> {{ $job->company->name }}
                            </h5>
                            <div class="mb-2">
                                <span class="badge bg-primary me-1">{{ $job->category->name }}</span>
                                <span class="badge bg-info text-dark me-1">
                                    <i class="bi bi-geo-alt"></i> {{ $job->location->name }}
                                </span>
                                @if($job->is_featured)
                                    <span class="badge bg-warning text-dark">Nổi bật</span>
                                @endif
                            </div>
                            @if($job->salary_min && $job->salary_max)
                                <p class="text-success fs-5 mb-0">
                                    <i class="bi bi-currency-dollar"></i> {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex mb-4">
                        @auth
                            @if(Auth::user()->isCandidate())
                                @php
                                    $hasApplied = Auth::user()->applications()->where('job_id', $job->id)->exists();
                                    $hasSaved = Auth::user()->savedJobs()->where('job_id', $job->id)->exists();
                                @endphp
                                
                                @if($hasApplied)
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="bi bi-check-circle"></i> Đã ứng tuyển
                                    </button>
                                @elseif($job->status !== 'approved' || $job->deadline < now())
                                    <button class="btn btn-secondary btn-lg" disabled>
                                        <i class="bi bi-x-circle"></i> Đã đóng tuyển dụng
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#applyModal">
                                        <i class="bi bi-send"></i> Ứng tuyển ngay
                                    </button>
                                @endif
                                
                                @if($hasSaved)
                                    <form action="{{ route('candidate.saved-jobs.destroy', $job->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning">
                                            <i class="bi bi-bookmark-fill"></i> Đã lưu
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('candidate.saved-jobs.store', $job->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="bi bi-bookmark"></i> Lưu tin
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-send"></i> Đăng nhập để ứng tuyển
                            </a>
                        @endauth
                    </div>

                    <hr>

                    <!-- Job Description -->
                    <div class="mb-4">
                        <h4 class="mb-3"><i class="bi bi-file-text"></i> Mô tả công việc</h4>
                        <div class="text-justify">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    <!-- Job Requirements -->
                    <div class="mb-4">
                        <h4 class="mb-3"><i class="bi bi-list-check"></i> Yêu cầu công việc</h4>
                        <div class="text-justify">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>

                    <!-- Skills Required -->
                    @if($job->skills->count() > 0)
                        <div class="mb-4">
                            <h4 class="mb-3"><i class="bi bi-gear"></i> Kỹ năng cần thiết</h4>
                            <div>
                                @foreach($job->skills as $skill)
                                    <span class="badge bg-secondary me-1 mb-1">{{ $skill->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Deadline -->
                    @if($job->deadline < now())
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle"></i> <strong>Đã hết hạn nộp hồ sơ!</strong> 
                            <br>Hạn cuối: {{ $job->deadline->format('d/m/Y H:i') }}
                            <br>Công việc này đã hết hạn từ {{ $job->deadline->diffForHumans() }}
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-clock-history"></i> <strong>Hạn nộp hồ sơ:</strong> {{ $job->deadline->format('d/m/Y') }}
                            <br><strong class="fs-5">Còn {{ $job->deadline->diffInDays(now()) }} ngày</strong> ({{ $job->deadline->diffForHumans() }})
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Jobs -->
            @if($relatedJobs->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-briefcase"></i> Việc làm liên quan</h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <h6>
                                    <a href="{{ route('jobs.show', $relatedJob->slug) }}" class="text-decoration-none">
                                        {{ $relatedJob->title }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-1">{{ $relatedJob->company->name }}</p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-geo-alt"></i> {{ $relatedJob->location->name }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Company Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Thông tin công ty</h5>
                </div>
                <div class="card-body">
                    @if($job->company->logo)
                        <img src="{{ asset('storage/' . $job->company->logo) }}" 
                             alt="{{ $job->company->name }}" 
                             class="img-fluid rounded mb-3">
                    @endif
                    <h5>{{ $job->company->name }}</h5>
                    
                    @if($job->company->size)
                        <p class="mb-2">
                            <i class="bi bi-people"></i> <strong>Quy mô:</strong> {{ $job->company->size }} nhân viên
                        </p>
                    @endif
                    
                    @if($job->company->address)
                        <p class="mb-2">
                            <i class="bi bi-geo-alt"></i> <strong>Địa chỉ:</strong> {{ $job->company->address }}
                        </p>
                    @endif
                    
                    @if($job->company->website)
                        <p class="mb-2">
                            <i class="bi bi-globe"></i> <strong>Website:</strong> 
                            <a href="{{ $job->company->website }}" target="_blank">{{ $job->company->website }}</a>
                        </p>
                    @endif

                    @if($job->company->description)
                        <hr>
                        <p class="small">{{ Str::limit($job->company->description, 200) }}</p>
                    @endif
                </div>
            </div>

            <!-- Job Info -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin chung</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="bi bi-tags"></i> <strong>Danh mục:</strong><br>
                        <span class="badge bg-primary mt-1">{{ $job->category->name }}</span>
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-geo-alt"></i> <strong>Địa điểm:</strong><br>
                        {{ $job->location->name }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-people"></i> <strong>Số lượng tuyển:</strong><br>
                        <span class="badge bg-success">{{ $job->positions }} người</span>
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-calendar"></i> <strong>Ngày đăng:</strong><br>
                        {{ $job->created_at->format('d/m/Y') }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-clock"></i> <strong>Hạn nộp:</strong><br>
                        {{ $job->deadline->format('d/m/Y') }}
                        @if($job->deadline < now())
                            <br><span class="badge bg-danger mt-1">Đã hết hạn</span>
                        @else
                            <br><span class="badge bg-warning text-dark mt-1">Còn {{ $job->deadline->diffInDays(now()) }} ngày</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ứng Tuyển -->
@auth
    @if(Auth::user()->isCandidate())
        <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="applyModalLabel">
                            <i class="bi bi-send"></i> Ứng tuyển: {{ $job->title }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('candidate.jobs.apply', $job->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            @if(!Auth::user()->cv_path)
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    Bạn chưa upload CV. 
                                    <a href="{{ route('candidate.cv.index') }}" class="alert-link">Upload CV ngay</a>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-file-earmark-pdf"></i> 
                                    <strong>CV của bạn:</strong> {{ basename(Auth::user()->cv_path) }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="cover_letter" class="form-label">Thư giới thiệu (không bắt buộc)</label>
                                <textarea class="form-control" id="cover_letter" name="cover_letter" rows="6"
                                          placeholder="Viết vài dòng giới thiệu về bản thân và lý do bạn phù hợp với vị trí này..."></textarea>
                                <small class="form-text text-muted">Tối đa 2000 ký tự</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary" {{ !Auth::user()->cv_path ? 'disabled' : '' }}>
                                <i class="bi bi-send"></i> Gửi hồ sơ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth
@endsection
