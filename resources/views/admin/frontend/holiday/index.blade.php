@extends('admin.master')
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Add your custom jQuery code here if needed -->
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
@section('admin_content')
    <div class="container">
    
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
   

         <div class="d-flex justify-content-between pt-5">
            <h2 style="color:green;font-family:'Courier New', Courier, monospace">Holiday List</h2>
                <a href="{{ route('holiday.create') }}" class="btn btn-primary mb-3">Add Holiday</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Holiday Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($holidays) --}}
                @forelse($holidays['data'] as $holiday)
                    <tr>
                        <td>{{ $holiday['holiday_name'] }}</td>
                        <td>{{ @$holiday['start_date'] }}</td>
                        <td>{{ @$holiday['end_date'] ?? '-' }}</td>
                        <td>{{ @$holiday['description'] ?? '-' }}</td>
                        <td>{{ @$holiday['status'] == 1 ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('holiday.edit', $holiday['id']) }}" class="btn btn-sm btn-info">Edit</a>
                            <form method="POST" action="{{ route('holiday.destroy', $holiday['id']) }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No holidays found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
