@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Quản lý Địa điểm</h1>
        <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Địa điểm
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
                            <th>Tên địa điểm</th>
                            <th>Slug</th>
                            <th>Số lượng việc làm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($locations as $location)
                        <tr>
                            <td>{{ $loop->iteration + ($locations->currentPage() - 1) * $locations->perPage() }}</td>
                            <td>{{ $location->name }}</td>
                            <td><code>{{ $location->slug }}</code></td>
                            <td>
                                <span class="badge bg-info">{{ $location->jobs_count }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.locations.edit', $location->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteLocation({{ $location->id }})"
                                            {{ $location->jobs_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </div>
                                <form id="delete-form-{{ $location->id }}" 
                                      action="{{ route('admin.locations.destroy', $location->id) }}" 
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
                                Chưa có địa điểm nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $locations->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function deleteLocation(id) {
    if (confirm('Bạn có chắc chắn muốn xóa địa điểm này?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
