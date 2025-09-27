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
                <label for=division_name_en>Division Name (English) <span class="text-danger">*</span></label>
                <input type="text" name=division_name_en class="form-control" value="{{ old('division_name_en', $division->division_name_en?? '') }}" required>
                @error('division_name_en')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for=division_name_bn>Division Name (Bangla)</label>
                <input type="text" name=division_name_bn class="form-control" value="{{ old('division_name_bn', $division->division_name_bn?? '') }}" required>
                @error('division_name_bn')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">{{ $division ? 'Update' : 'Save' }}</button>
            <a href="{{ route('division.list') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
