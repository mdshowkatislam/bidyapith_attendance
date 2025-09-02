@extends('admin.master')


@section('admin_content')
    <div class="container">
        <h2>Special Working Days List</h2>
        <a href="{{ route('special_working_day.create') }}"
           class="btn btn-primary mb-3">Add New</a>

        <table class="table table-bordered"
               id="specialWorkingDaysTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Day Name</th>
                    <th>Day Type</th>
                    <th>Description</th>
                    <th>Groups</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($special_working_days as $day)
                    <tr>
                        <td>{{ $day['id'] }}</td>
                        <td>{{ $day['date'] }}</td>
                        <td>{{ Carbon\Carbon::parse($day['date'])->dayName }}</td>
                        <td>{{ $day['day_type'] == 1 ? 'Working Day' : 'Off Day' }}</td>
                        <td>{{ $day['description'] ?? '' }}</td>
                        <td>
                            @foreach ($day['groups'] as $group)
                                <span class="badge bg-info text-dark">{{ $group['group_name'] }}</span>
                            @endforeach
                        </td>
                        <td>{{ $day['status'] == 1 ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('special_working_day.edit', $day['id']) }}"
                               class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('special_working_day.destroy', $day['id']) }}"
                                  method="POST"
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            class="text-center">No records found</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
@endsection
