@extends('admin.master')

@php
    $title = 'Section List';
    $header = 'All Sections';
@endphp

@section('admin_content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('section.add', 0) }}" class="btn btn-primary">Add Section</a>
    </div>
    <div class="card-body">
         @if(session('success'))
            <div class="alert alert-success pt-2" id="alert-success">{{ session('success') }}</div>
         @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Section Name</th>
                    <th>Department</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $index => $sec)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sec->name }}</td>
                    <td>{{ $sec->department->name ?? '-' }}</td>
                    <td>{{ $sec->created_at->format('d M, Y') }}</td>
                    <td>
                        <a href="{{ route('section.edit', $sec->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('section.delete', $sec->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this section?');">
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


