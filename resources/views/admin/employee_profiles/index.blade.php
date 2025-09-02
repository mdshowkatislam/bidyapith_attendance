@extends('admin.master')


@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success pt-2"
                 id="alert-success">{{ session('success') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
            <h4>Employee List</h4>
            <a href="{{ route('employee_profile.add') }}"
               class="btn btn-primary">+ Add Employee</a>
        </div>

        <!-- Search & Filter Form -->
        <form method="GET"
              action="{{ route('employee_profile.index') }}"
              class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search by name, badge, NID or phone">
                </div>
                <div class="col-md-2">
                    <select name="division_id"
                            class="form-control">
                        <option value="">All Divisions</option>
                        @foreach ($divisions as $division)
                            <option value="{{ $division->id }}"
                                    {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="department_id"
                            class="form-control">
                        <option value="">All Departments</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                    {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="section_id"
                            class="form-control">
                        <option value="">All Sections</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}"
                                    {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit"
                            class="btn btn-secondary w-100">Filter</button>
                </div>
            </div>
        </form>

        <!-- Employee Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Photo</th>
                        <th>Badge</th>
                        <th>NID</th>
                        <th>Phone</th>
                        <th>Division</th>
                        <th>Department</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th>Change Status</th>
                        <th>Action</th>


                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $index => $employee)
                        <tr>
                            <td>{{ $employees->firstItem() + $index }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>

                                @if ($employee->picture)
                                    <img src="{{ asset('storage/' . $employee->picture) }}"
                                         alt="Photo"
                                         width="50"
                                         height="50"
                                         class="rounded-circle">
                                @else
                                    <img src="{{ asset('images/default.png') }}"
                                         alt="No Photo"
                                         width="50"
                                         height="50"
                                         class="rounded-circle">
                                @endif
                            </td>
                            <td>{{ $employee->badgenumber }}</td>
                            <td>{{ $employee->nid }}</td>
                            <td>{{ $employee->mobile_number }}</td>
                            <td>{{ $employee->division->name ?? '-' }}</td>
                            <td>{{ $employee->department->name ?? '-' }}</td>
                            <td>{{ $employee->section->name ?? '-' }}</td>
                            <td>
                                @if ($employee->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>


                                <!-- Toggle status -->
                                <form method="POST"
                                      action="{{ route('employee_profile.toggleStatus', $employee->id) }}"
                                      style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="btn btn-sm {{ $employee->status ? 'btn-danger' : 'btn-success' }}">
                                        {{ $employee->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>

                            <td>
                                <a href="{{ route('employee_profile.edit', $employee->id) }}"
                                   class="btn btn-sm btn-info mb-1">Edit</a>
                                <form action="{{ route('employee_profile.destroy', $employee->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure to delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Del</button>
                                </form>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9"
                                class="text-center">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $employees->withQueryString()->links() }}
        </div>
    </div>
@endsection

<script>
    setTimeout(() => {
        const alert_msg = document.getElementById('alert-success');
        if (alert) {
            alert_msg.style.transition = 'opacity 0.5s ease';
            alert_msg.style.background = 'gray';
            alert_msg.style.opacity = '0';
            setTimeout(() => alert_msg.remove(), 500);
        }
    }, 3000); // Hide after 3 seconds
</script>
