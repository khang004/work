@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý người dùng</h1>
    <div>
        <a href="{{ route('admin.users.pending') }}" class="btn btn-warning">
            <i class="bi bi-hourglass-split"></i> Tài khoản chờ duyệt
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Lọc theo vai trò</label>
                    <select class="form-select" name="role" onchange="this.form.submit()">
                        <option value="all" {{ $role == 'all' ? 'selected' : '' }}>Tất cả</option>
                        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="employer" {{ $role == 'employer' ? 'selected' : '' }}>Nhà tuyển dụng</option>
                        <option value="candidate" {{ $role == 'candidate' ? 'selected' : '' }}>Ứng viên</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role == 'employer')
                                <span class="badge bg-success">Nhà tuyển dụng</span>
                            @else
                                <span class="badge bg-primary">Ứng viên</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_approved)
                                <span class="badge bg-success">Đã duyệt</span>
                            @else
                                <span class="badge bg-warning">Chờ duyệt</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->role == 'employer')
                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($user->is_approved)
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Xác nhận khóa tài khoản này?')">
                                            <i class="bi bi-lock"></i> Khóa
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Xác nhận mở khóa tài khoản này?')">
                                            <i class="bi bi-unlock"></i> Mở khóa
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $users->links() }}
</div>
@endsection
