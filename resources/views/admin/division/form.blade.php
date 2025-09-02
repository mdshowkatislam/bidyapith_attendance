@extends('admin.master')

@php
    $title = $division ? 'Edit Division' : 'Add Division';
    $header = $title;
@endphp

@section('admin_content')
<div class="card">
    <div class="card-body">
        <form action="{{ $division ? route('division.update', $division->id) : route('division.store') }}" method="POST">
            @csrf
            @if($division)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Division Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $division->name ?? '') }}" required>
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ $division ? 'Update' : 'Save' }}</button>
            <a href="{{ route('division.list') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
