@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Blog Slider Management</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Add New Slider</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sliders as $slider)
            <tr>
                <td>{{ $slider->id }}</td>
                <td>
                    {{-- @dd($slider->image) --}}
                    @if($slider->image)
                        <img src="{{ asset('' . $slider->image) }}" width="80">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $slider->title }}</td>
                <td>{{ Str::limit($slider->description, 50) }}</td>
                <td>
                    <button class="btn btn-sm btn-info text-white editBtn" data-id="{{ $slider->id }}">Edit</button>
                    <form action="{{ route('blog-sliders.destroy', $slider->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this slider?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $sliders->links() }}
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('blog-sliders.store') }}" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add New Slider</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label>Title</label>
        <input type="text" name="title" class="form-control mb-2">
        <label>Description</label>
        <textarea name="description" class="form-control mb-2"></textarea>
        <label>Image</label>
        <input type="file" name="image" class="form-control">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" id="editForm" enctype="multipart/form-data" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title">Edit Slider</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label>Title</label>
        <input type="text" name="title" id="editTitle" class="form-control mb-2">
        <label>Description</label>
        <textarea name="description" id="editDescription" class="form-control mb-2"></textarea>
        <label>Change Image</label>
        <input type="file" name="image" class="form-control">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>
@stop
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('.editBtn').on('click', function () {
            let id = $(this).data('id');

            $.get('/admin/blog-sliders/' + id + '/edit', function (data) {
                $('#editTitle').val(data.title);
                $('#editDescription').val(data.description);
                $('#editForm').attr('action', '/admin/blog-sliders/' + id);

                let modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            });
        });
    });
</script>



