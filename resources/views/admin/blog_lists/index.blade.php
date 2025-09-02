@extends('adminlte::page')

@section('title', 'Blog Lists')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Blog List Management</h2>
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addModal">Add New Blog</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($blogs as $blog)
            <tr>
                <td>{{ $blog->id }}</td>
                <td>
                    @if($blog->image)
                        <img src="{{ asset($blog->image) }}" width="80">
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $blog->title }}</td>
                <td>{{ $blog->Category_type }}</td>
                <td>{{ $blog->type }}</td>
                <td>{{ $blog->status }}</td>
                <td>
                    <button class="btn btn-sm btn-info text-white editBtn" data-id="{{ $blog->id }}">Edit</button>
                    <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this blog?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $blogs->links() }}
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">

  <div class="modal-dialog">
    <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add New Blog</h5>
        
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <label>Title</label>
        <input type="text" name="title" class="form-control mb-2">
        <label>Description</label>
        <textarea name="description" class="form-control mb-2"></textarea>
        <label>Image Title</label>
        <input type="text" name="image_title" class="form-control mb-2">
        <label>Category</label>
        <select name="Category_type" class="form-control mb-2">
            @foreach($categories as $category)
                <option value="{{ $category->name }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <label>Type</label>
        <select name="type" class="form-control mb-2">
            <option value="1">Recent</option>
            <option value="2">Popular</option>
            <option value="3">Featured</option>
            <option value="4">Trending</option>
            <option value="5">Editor's Choice</option>
        </select>
        <label>Status</label>
        <select name="status" class="form-control mb-2">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
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
        <h5 class="modal-title">Edit Blog</h5>
        <button type="button" class="close" data-dismiss="modal"></button>
   
      </div>
      <div class="modal-body">
        <label>Title</label>
        <input type="text" name="title" id="editTitle" class="form-control mb-2">
        <label>Description</label>
        <textarea name="description" id="editDescription" class="form-control mb-2"></textarea>
        <label>Image Title</label>
        <input type="text" name="image_title" id="editImageTitle" class="form-control mb-2">
        <label>Category</label>
        <select name="Category_type" id="editCategory" class="form-control mb-2">
            @foreach($categories as $category)
                <option value="{{ $category->name }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <label>Type</label>
        <select name="type" id="editType" class="form-control mb-2">
            <option value="1">Recent</option>
            <option value="2">Popular</option>
            <option value="3">Featured</option>
            <option value="4">Trending</option>
            <option value="5">Editor's Choice</option>
        </select>
        <label>Status</label>
        <select name="status" id="editStatus" class="form-control mb-2">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    $(document).ready(function() {
        $('.editBtn').on('click', function() {
            let id = $(this).data('id');
            $.get('/admin/blog/' + id + '/edit', function(data) {
                $('#editTitle').val(data.title);
                $('#editDescription').val(data.description);
                $('#editImageTitle').val(data.image_title);
                $('#editCategory').val(data.Category_type);
                $('#editType').val(data.type);
                $('#editStatus').val(data.status);
                $('#editForm').attr('action', '/admin/blog/' + id);
                $('#editModal').modal('show');
            });
        });
    });
</script>
