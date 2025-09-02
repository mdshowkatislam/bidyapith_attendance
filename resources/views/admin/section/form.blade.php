@extends('admin.master')

@php
    $title = $section ? 'Edit Section' : 'Add Section';
    $header = $title;
@endphp

@section('admin_content')
<div class="card">
    <div class="card-body">
        <form action="{{ $section ? route('section.update', $section->id) : route('section.store') }}" method="POST">
            @csrf
            @if($section)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Section Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $section->name ?? '') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="department_id">Select Department</label>
                <select name="department_id" class="form-control" required>
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ (old('department_id', $section->department_id ?? '') == $dept->id) ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ $section ? 'Update' : 'Save' }}</button>
            <a href="{{ route('section.list') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
