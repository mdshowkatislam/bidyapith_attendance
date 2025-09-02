@extends('admin.master')

@php
    $title = 'Division List';
    $header = 'All Divisions';
@endphp

@section('admin_content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('division.add', 0) }}" class="btn btn-primary">Add Division</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($divisions as $index => $division)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $division->name }}</td>
                    <td>{{ $division->created_at->format('d M, Y') }}</td>
                    <td>
                        <a href="{{ route('division.edit', $division->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <a href="{{ route('division.delete', $division->id) }}" class="btn btn-sm btn-danger">Del</a>
                        {{-- Future: Add delete button --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
