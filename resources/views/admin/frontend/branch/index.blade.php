@extends('admin.master')

@php
    $title = 'Shift Management';
    $header = $title;
@endphp

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

@section('admin_content')
@if (session('success'))
    <script>
        $(document).ready(function () {
            toastr.success("{{ session('success') }}");
        });
    </script>
@endif
    <div class="container pt-4">
        <div class="d-flex justify-content-between">
            <h2 style="color:green;font-family:'Courier New', Courier, monospace">Branch List</h2>
            <a href="{{ route('branch.create') }}" class="btn btn-success mb-3">Add New Branch</a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Branch Name</th>
                    <th>Branch Location</th>
                    <th>Head of Branch</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branch)
                    <tr>
                        <td>{{ $branch['id'] }}</td>
                        <td>{{ $branch['branch_name_en'] }}</td>
                        <td>{{ $branch['branch_location'] }}</td>
                        <td>{{ $branch['head_of_branch_id'] }}</td>
                        <td>{{ $branch['rec_status'] == 1 ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('branch.edit', $branch['uid']) }}" class="btn btn-sm btn-primary">Edit</a>
                            <a href="{{ route('branch.destroy', $branch['uid']) }}"
                               onclick="return confirm('Are you sure?')"
                               class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No data found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

    <!-- jQuery and Toastr -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@section('scripts')
    <script>
        $(document).ready(function () {
            alert
            const successMsg = localStorage.getItem('success_msg');
            if (successMsg) {
                toastr.success(successMsg);
                localStorage.removeItem('success_msg');
            }

            // Fallback for Laravel session flash (if any)
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
        });
    </script>
  
   
    <script>
        $(document).ready(function() {
            // Example: Highlight row on hover
            $('table tbody tr').hover(
                function() { $(this).addClass('table-active'); },
                function() { $(this).removeClass('table-active'); }
            );
        });
    </script>

@endsection
