@extends('admin.master')

@php
    $title = $department ? 'Edit Department' : 'Add Department';
    $header = $title;
@endphp

@section('admin_content')
<div class="card">
    <div class="card-body">
        <form action="{{ $department ? route('department.update', $department->id) : route('department.store') }}" method="POST">
            @csrf
            @if($department)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Department Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $department->name ?? '') }}" required>
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="division_id">Select Division</label>
                <select name="division_id" class="form-control" required>
                    <option value="">-- Select Division --</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div->id }}" {{ (old('division_id', $department->division_id ?? '') == $div->id) ? 'selected' : '' }}>
                            {{ $div->name }}
                        </option>
                    @endforeach
                </select>
                @error('division_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ $department ? 'Update' : 'Save' }}</button>
            <a href="{{ route('department.list') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
