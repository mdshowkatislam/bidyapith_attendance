@extends('admin.master')

@php
    $title = 'District List';
    $header = 'All Districts';
@endphp

@section('admin_content')
<div class="card">
    <div class="card-header d-flex justify-content-end">
        <a href="{{ route('district.add', 0) }}" class="btn btn-primary float-end">Add District</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>District Name (English)</th>
                    <th>District Name (Bengali)</th>
                    <th>Division</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($districts as $index => $district)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $district->district_name_en }}</td>
                    <td>{{ $district->district_name_bn }}</td>
                    <td>{{ $district->division->id ?? '-' }}</td>
                    <td>{{ $district->created_at->format('d M, Y') }}</td>
                    <td>
                        <a href="{{ route('district.edit', $district->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('district.delete', $district->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this district?');">
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
