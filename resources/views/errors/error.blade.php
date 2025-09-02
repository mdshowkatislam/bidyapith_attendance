@extends('admin.master')

@section('admin_content')
<div class="container py-5">
    <div class="alert alert-danger text-center" role="alert">
        <h4 class="alert-heading">Error!</h4>
        {{-- @dd(session('error')) --}}
        <p>{{ session('error') ? session('error') : 'Something went wrong. Please try again later.' }}</p>
        <hr>
        <a href="{{ route('group_manage.index') }}" class="btn btn-primary">Back to Group List</a>
    </div>
</div>
@endsection
