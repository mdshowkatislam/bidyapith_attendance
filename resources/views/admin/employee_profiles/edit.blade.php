@extends('adminlte::page')

@section('title', 'Edit Employee')

@section('content_header')
    <h1>Edit Employee</h1>
@endsection

@section('content')
    <form action="{{ route('employee_profile.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.employee_profiles.partials.form', ['employee' => $employee])
        <button class="btn btn-success" type="submit">Update</button>
    </form>
@endsection
