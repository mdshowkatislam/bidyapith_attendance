@extends('adminlte::page')

@section('title', 'Dashboard')


@section('content')
    <div class="container">
        <h2 class="mb-4 text-primary">🗂️ Blog Category Management</h2>
        <!-- Show validation error in Add Modal -->
        @if ($errors->store->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->store->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Search & Add -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET"
                      action="{{ route('blog-categories.index') }}">
                    <div class="input-group">
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               class="form-control"
                               placeholder="Search category...">
                        <button class="btn btn-outline-primary">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#addModal">+ Add Category</button>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $key => $cat)
                            <tr>
                                <td>{{ $categories->firstItem() + $key }}</td>
                                <td class="fw-bold">{{ $cat->name }}</td>
                                <td>{{ $cat->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <a href="#"
                                       class="btn btn-sm btn-info text-white editBtn"
                                       data-id="{{ $cat->id }}">View/Edit</a>
                                    <form action="{{ route('blog-categories.destroy', $cat->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="text-center">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade"
         id="addModal"
         tabindex="-1">
        <div class="modal-dialog">
            <form method="POST"
                  action="{{ route('blog-categories.store') }}"
                  class="modal-content">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Category Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade"
         id="editModal"
         tabindex="-1">
        <div class="modal-dialog">
            <form method="POST"
                  id="editForm"
                  class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Category Name</label>
                    <input type="text"
                           name="name"
                           id="editName"
                           class="form-control"
                           required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-info">Update</button>
                </div>
            </form>
        </div>
    </div>
@stop

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        $('.editBtn').on('click', function(e) {
            e.preventDefault();

            let id = $(this).data('id');

            // Debugging: log the ID
            console.log("Clicked edit for ID:", id);

            $.ajax({
                url: '/admin/blog-categories/' + id + '/edit',
                type: 'GET',
                success: function(data) {
                    $('#editName').val(data.name);
                    $('#editForm').attr('action', '/admin/blog-categories/' + id);
                    $('#editModal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
