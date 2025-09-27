@extends('admin.master')

@section('content')
    <div class="container-fluid">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3 pt-4">
            <h4>Employee List</h4>
            <a href="{{ route('employee_profile.add') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Employee
            </a>
        </div>

        <!-- Search & Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('employee_profile.index') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <!-- Search Input -->
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Name, badge, NID or phone">
                        </div>

                        <!-- Division Dropdown -->
                        <div class="col-md-2">
                            <label class="form-label">Division</label>
                            <select name="division_id" id="division_id" class="form-control">
                                <option value="">All Divisions</option>
                                @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}"
                                        {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->division_name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- District Dropdown (Dynamic) -->
                        <div class="col-md-2">
                            <label class="form-label">District</label>
                            <select name="district_id" id="district_id" class="form-control" {{ !request('division_id') ? 'disabled' : '' }}>
                                <option value="">All Districts</option>
                                @if(request('division_id'))
                                    @foreach ($districts->where('division_id', request('division_id')) as $district)
                                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->district_name_en ?? $district->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Upazila Dropdown (Dynamic) -->
                        <div class="col-md-2">
                            <label class="form-label">Upazila</label>
                            <select name="upazila_id" id="upazila_id" class="form-control" {{ !request('district_id') ? 'disabled' : '' }}>
                                <option value="">All Upazilas</option>
                                @if(request('district_id'))
                                    @foreach ($upazilas->where('district_id', request('district_id')) as $upazila)
                                        <option value="{{ $upazila->id }}" {{ request('upazila_id') == $upazila->id ? 'selected' : '' }}>
                                            {{ $upazila->upazila_name_en ?? $upazila->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('employee_profile.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Badge</th>
                                <th>NID</th>
                                <th>Phone</th>
                                <th>Division</th>
                                <th>District</th>
                                <th>Upazila</th>
                                <th>Status</th>
                                <th>Change Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $index => $employee)
                                <tr>
                                    <td>{{ $employees->firstItem() + $index }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>
                                        @if ($employee->picture)
                                            <img src="{{ asset('storage/' . $employee->picture) }}" alt="Photo" width="50" height="50" class="rounded-circle">
                                        @else
                                            <img src="{{ asset('images/default.png') }}" alt="No Photo" width="50" height="50" class="rounded-circle">
                                        @endif
                                    </td>
                                    <td>{{ $employee->badgenumber }}</td>
                                    <td>{{ $employee->nid }}</td>
                                    <td>{{ $employee->mobile_number }}</td>
                                    <td>{{ $employee->division->name ?? '-' }}</td>
                                    <td>{{ $employee->district->district_name_en ?? '-' }}</td>
                                    <td>{{ $employee->upazila->upazila_name_en ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->status ? 'success' : 'secondary' }}">
                                            {{ $employee->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('employee_profile.toggleStatus', $employee->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $employee->status ? 'btn-warning' : 'btn-success' }}" onclick="return confirm('Are you sure?')">
                                                {{ $employee->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('employee_profile.edit', $employee->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('employee_profile.destroy', $employee->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this employee?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No employees found.</p>
                                        <a href="{{ route('employee_profile.add') }}" class="btn btn-primary">Add First Employee</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($employees->hasPages())
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} entries
                        </div>
                        {{ $employees->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* small helper if you need more vertical padding for the selects */
        .form-control { min-height: 38px; }
    </style>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const divisionSelect = document.getElementById('division_id');
    const districtSelect = document.getElementById('district_id');
    const upazilaSelect  = document.getElementById('upazila_id');

    // Division change → load Districts
    divisionSelect.addEventListener('change', function () {
        const divisionId = this.value;

        if (divisionId) {
            districtSelect.disabled = false;

            fetch(`/employee_profile/get-districts/${divisionId}`)
                .then(response => response.json())
                .then(data => {
                    districtSelect.innerHTML = '<option value="">All Districts</option>';
                    data.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.id}">${district.district_name_en}</option>`;
                    });

                    upazilaSelect.disabled = true;
                    upazilaSelect.innerHTML = '<option value="">All Upazilas</option>';
                })
                .catch(() => alert("Failed to fetch districts"));
        } else {
            districtSelect.disabled = true;
            districtSelect.innerHTML = '<option value="">All Districts</option>';
            upazilaSelect.disabled = true;
            upazilaSelect.innerHTML = '<option value="">All Upazilas</option>';
        }
    });

    // District change → load Upazilas
    districtSelect.addEventListener('change', function () {
        const districtId = this.value;

        if (districtId) {
            upazilaSelect.disabled = false;

            fetch(`/employee_profile/get-upazilas/${districtId}`)
                .then(response => response.json())
                .then(data => {
                    upazilaSelect.innerHTML = '<option value="">All Upazilas</option>';
                    data.forEach(upazila => {
                        upazilaSelect.innerHTML += `<option value="${upazila.id}">${upazila.upazila_name_en}</option>`;
                    });
                })
                .catch(() => alert("Failed to fetch upazilas"));
        } else {
            upazilaSelect.disabled = true;
            upazilaSelect.innerHTML = '<option value="">All Upazilas</option>';
        }
    });
});
</script>



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
