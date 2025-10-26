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
            <!-- Removed Add Employee button since data comes from external API -->
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
                                   placeholder="Profile ID, CAID or EIIN">
                        </div>

                        <!-- Person Type Filter -->
                        <div class="col-md-2">
                            <label class="form-label">Person Type</label>
                            <select name="person_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="1" {{ request('person_type') == '1' ? 'selected' : '' }}>Teacher</option>
                                <option value="2" {{ request('person_type') == '2' ? 'selected' : '' }}>Staff</option>
                                <option value="3" {{ request('person_type') == '3' ? 'selected' : '' }}>Student</option>
                            </select>
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

                        <!-- Action Buttons - Centered -->
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-center gap-3">
                                <button type="submit" class="btn btn-secondary" style="min-width: 120px;margin-right: 3px;">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('employee_profile.index') }}" class="btn btn-outline-secondary" style="min-width: 120px;margin-left: 3px;">
                                    <i class="fas fa-refresh"></i> Reset
                                </a>
                            </div>
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
                                <th>Profile ID</th>
                                <th>Name</th>
                                <th>Photo</th>
                                <th>Person Type</th>
                                <th>Designation</th>
                                <th>Phone</th>
                                <th>Division</th>
                                <th>District</th>
                                <th>Upazila</th>
                                <th>CAID</th>
                                <th>EIIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $index => $employee)
                                <tr>
                                    <td>{{ $employees->firstItem() + $index }}</td>
                                    <td>{{ $employee['profile_id'] }}</td>
                                    <td>{{ $employee['name'] }}</td>
                                    <td>
                                        @if ($employee['picture'])
                                            <img src="{{ $employee['picture'] }}" alt="Photo" width="50" height="50" class="rounded-circle" onerror="this.src='{{ asset('images/default.png') }}'">
                                        @else
                                            <img src="{{ asset('images/default.png') }}" alt="No Photo" width="50" height="50" class="rounded-circle">
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $employee['person_type'] == 1 ? 'primary' : ($employee['person_type'] == 2 ? 'info' : 'warning') }}">
                                            {{ $employee['person_type_text'] }}
                                        </span>
                                    </td>
                                    <td>{{ $employee['designation'] ?? '-' }}</td>
                                    <td>{{ $employee['mobile_number'] ?? '-' }}</td>
                                    <td>{{ $employee['division_name'] ?? '-' }}</td>
                                    <td>{{ $employee['district_name'] ?? '-' }}</td>
                                    <td>{{ $employee['upazila_name'] ?? '-' }}</td>
                                    <td>{{ $employee['caid'] ?? '-' }}</td>
                                    <td>{{ $employee['eiin'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No employees found.</p>
                                        <p class="text-sm text-muted">Employee data is synchronized from external systems.</p>
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
        .form-control { min-height: 38px; }
        .table img { object-fit: cover; }
    </style>
@endsection

@section('scripts')
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

    // Auto-hide success alert
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
});
</script>
@endsection