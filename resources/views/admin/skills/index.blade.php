@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Quản lý Kỹ năng</h1>
        <a href="{{ route('admin.skills.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Kỹ năng
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên kỹ năng</th>
                            <th>Slug</th>
                            <th>Số lượng việc làm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($skills as $skill)
                        <tr>
                            <td>{{ $loop->iteration + ($skills->currentPage() - 1) * $skills->perPage() }}</td>
                            <td>{{ $skill->name }}</td>
                            <td><code>{{ $skill->slug }}</code></td>
                            <td>
                                <span class="badge bg-info">{{ $skill->jobs_count }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.skills.edit', $skill->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteSkill({{ $skill->id }})"
                                            {{ $skill->jobs_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </div>
                                <form id="delete-form-{{ $skill->id }}" 
                                      action="{{ route('admin.skills.destroy', $skill->id) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Chưa có kỹ năng nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $skills->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function deleteSkill(id) {
    if (confirm('Bạn có chắc chắn muốn xóa kỹ năng này?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
