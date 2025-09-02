@extends('admin.master')

@php
    $title = 'Department List';
    $header = 'All Departments';
@endphp

@section('admin_content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('department.add', 0) }}" class="btn btn-primary">Add Department</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th>Division</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $index => $dept)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dept->name }}</td>
                    <td>{{ $dept->division->name ?? '-' }}</td>
                    <td>{{ $dept->created_at->format('d M, Y') }}</td>
                    <td>
                        <a href="{{ route('department.edit', $dept->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('department.delete', $dept->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this department?');">
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
