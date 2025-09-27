@extends('admin.master')

@php
    $title = 'Upazila List';
    $header = 'All Upazilas';
@endphp

@section('admin_content')
<div class="card">
    <div class="card-header d-flex justify-content-end">
        <a href="{{ route('upazila.add', 0) }}" class="btn btn-primary">Add Upazila</a>
    </div>
    <div class="card-body">
         @if(session('success'))
            <div class="alert alert-success pt-2" id="alert-success">{{ session('success') }}</div>
         @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Upazila Name Bangla</th>
                    <th>Upazila Name English</th>
                    <th>District</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upazilas as $index => $upazila)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $upazila->upazila_name_bn }}</td>
                    <td>{{ $upazila->upazila_name_en }}</td>
                    <td>{{ $upazila->district->id ?? '-' }}</td>
                    <td>{{ $upazila->created_at->format('d M, Y') }}</td>
                    <td>
                        <a href="{{ route('upazila.edit', $upazila->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('upazila.delete', $upazila->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this upazila?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

  <script>
  setTimeout(() => {
      const alert = document.getElementById('alert-success');
      if (alert) {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }
    }, 3000); 
  </script>


