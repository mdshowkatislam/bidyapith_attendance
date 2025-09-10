@extends('admin.master')

@php
    $title = 'Shift Management';
    $header = $title;
@endphp

@section('admin_content')
    <div class="container pt-4">
        <div class="d-flex justify-content-between">
            <h2 style="color:green;font-family:'Courier New', Courier, monospace">Shift List</h2>
            <a href="{{ route('shift.create') }}" class="btn btn-success mb-3">Add New Shift</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Branch</th>
                    <th>Shift Name (EN)</th>
                    <th>Shift Name (BN)</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shifts as $shift)
                    <tr>
                        <td>{{ $shift['id'] }}</td>
                        <td>{{ $shift['branch']['branch_name_en'] }}</td>
                        <td>{{ $shift['shift_name_en'] }}</td>
                        <td>{{ $shift['shift_name_bn'] }}</td>
                        <td>{{ $shift['start_time'] }}</td>
                        <td>{{ $shift['end_time'] }}</td>
                        <td>{{ $shift['status'] == 1 ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('shift.edit', $shift['uid']) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('shift.destroy', $shift['uid']) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script>
        $(document).ready(function() {
            // Highlight row on hover
            $('table tbody tr').hover(
                function() { $(this).addClass('table-active'); },
                function() { $(this).removeClass('table-active'); }
            );
        });
    </script>
@endsection