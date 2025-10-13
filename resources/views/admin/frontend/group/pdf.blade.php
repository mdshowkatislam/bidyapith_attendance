<!DOCTYPE html>
<html>
<head>
    <title>Group Details - {{ $group['group_name'] ?? 'N/A' }}</title>
    <style>
        .flex-row-between {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .pdf-container {
            padding: 10px;
        }

        .header {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .section {
            margin-bottom: 10px;
        }

        .section h3 {
            margin-bottom: 8px;
            color: #007BFF;
            font-size: 12px;
        }

        .details p {
            margin: 2px 0;
            font-size: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 8px;
        }

        .table th {
            background-color: #343A40;
            color: white;
            padding: 4px;
            border: 1px solid #dee2e6;
            font-size: 9px;
        }

        .table td {
            border: 1px solid #dee2e6;
            padding: 3px;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        @page {
            margin: 10px;
            size: A4 landscape;
        }

        .download-btn {
            display: none;
        }
    </style>
</head>
<body>
    <div class="pdf-container">
        {{-- Header --}}
        <div class="header">
            <h2><strong>Group Name: </strong> {{ $group['group_name'] ?? 'N/A' }}</h2>
        </div>

        {{-- Group Information --}}
        <div class="flex-row-between">
            <div class="section details">
                <p><strong>Description:</strong> {{ $group['description'] ?? 'N/A' }}</p>
                <p><strong>Branch:</strong> {{ $group['branch']['branch_name_en'] ?? 'N/A' }}</p>
                <p><strong>Shift:</strong> {{ $group['shift']['shift_name_en'] ?? 'N/A' }}</p>
                <p><strong>Shift Time:</strong> {{ ($group['shift']['shift_start_time'] ?? 'N/A') . ' - ' . ($group['shift']['shift_end_time'] ?? 'N/A') }}</p>
                <p><strong>Working Days:</strong>
                    {{ collect($group['work_days'] ?? [])->pluck('day_name')->implode(', ') }}
                </p>
            </div>
            <div class="section details" style="text-align: right;">
                <p><strong>Flexible In Time:</strong> {{ $group['flexible_in_time'] ?? 0 }} minutes</p>
                <p><strong>Flexible Out Time:</strong> {{ $group['flexible_out_time'] ?? 0 }} minutes</p>
                <p><strong>Total Employees:</strong> {{ $group['employee_count'] ?? 0 }}</p>
                <p><strong>Created:</strong> {{ isset($group['created_at']) ? \Carbon\Carbon::parse($group['created_at'])->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>

        {{-- Employees Table --}}
        <div class="section">
            <h3>Employee List ({{ count($group['employees'] ?? []) }} employees)</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile ID</th>
                        <th>Person Type</th>

                        <th>Name</th>
                        <th>Designation</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Division</th>
                        <th>District</th>
                        <th>Upazila</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($group['employees'] ?? [] as $index => $employee)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $employee['profile_id'] ?? 'N/A' }}</td>
                             @php
                              $personType= $employee['person_type'] ?? 'N/A';
                              $personText= match($personType){
                                  1=>'teacher',
                                  2=>'staff',
                                  3=>'student',
                              };
                             @endphp

                            <td>{{ $employee['personText'] ?? 'N/A' }}</td>

                            <td>{{ $employee['name'] ?? 'N/A' }}</td>
                            <td>{{ $employee['designation'] ?? 'N/A' }}</td>
                            <td>{{ $employee['mobile_number'] ?? 'N/A' }}</td>
                            <td>{{ $employee['present_address'] ?? 'N/A' }}</td>
                            <td>{{ $employee['division'] ?? 'N/A' }}</td>
                            <td>{{ $employee['district'] ?? 'N/A' }}</td>
                            <td>{{ $employee['upazila'] ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center;">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>