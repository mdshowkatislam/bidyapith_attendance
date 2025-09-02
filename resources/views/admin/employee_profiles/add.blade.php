@extends('adminlte::page')

@section('title', 'Add Employee')

@section('content_header')
    <h1>Add Employee</h1>
@endsection

@section('content')

    <form action="{{ route('employee_profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')

        @include('admin.employee_profiles.partials.form' )
           <button class="btn btn-success" type="submit">Save</button>
    </form>
@endsection
