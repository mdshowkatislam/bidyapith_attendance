{{-- resources/views/admin/master.blade.php --}}
@extends('adminlte::page')

@section('title', $title ?? 'Attendance Admin')

@section('content')
    @stack('css')
    @yield('css')

    @yield('admin_content')


    @yield('scripts')
    @stack('scripts')


@stop
