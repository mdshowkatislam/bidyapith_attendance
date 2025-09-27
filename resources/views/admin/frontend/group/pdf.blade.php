<!DOCTYPE html>
<html>

<head>
    <title>Group Details - {{ $group['group_name'] }}</title>
    <style>
        .flex-row-between {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
             width: 100%;
        }

        .pdf-container {
            padding: 14px;
        }

        .header {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 15px;
        }

        .section h3 {
            margin-bottom: 10px;
            color: #007BFF;
        }

        .details p {
            margin: 2px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th {
            background-color: #343A40;
            color: white;
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        .table td {
            border: 1px solid #dee2e6;
            padding: 6px;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .download-btn {
            margin-bottom: 15px;
            display: inline-block;
            background-color: #28A745;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 4px;
        }


        @media print {
            .download-btn {
                display: none;
            }
        }
         @page {
            margin: 20px;
            size: A4 landscape; /* or portrait */
        }
    </style>
</head>

<body>

    <div class="pdf-container">

        {{-- PDF Download Button (Only visible in browser) --}}
        @if (!request()->has('pdf'))
            <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                <a href="{{ route('group_manage.download.pdf', $group['id']) }}"
                   class="download-btn"
                   target="_blank">Download PDF</a>
            </div>
        @endif
        {{-- <td>{{ $item['flexible_in_time'] }} min</td>
                    <td>{{ $item['flexible_out_time'] }} min</td> --}}

        {{-- Header --}}
        <div class="header">
            <h2> <strong>Group Name: </strong> {{ $group['group_name'] }}</h2>
        </div>

        {{-- Group Information --}}
        <div class="flex-row-between">
            <div class="section details">
                <p><strong>Description:</strong> {{ $group['description'] }}</p>
                <p><strong>Branch:</strong> {{ $group['branch']['branch_name_en'] ?? 'N/A' }} ({{ $group['branch']['branch_code'] ?? 'N/A' }})</p>
                <p><strong>Shift:</strong> {{ $group['shift']['shift_name_en'] ?? 'N/A' }}</p>
                <p><strong>Working Days:</strong>
                    {{-- {{ collect($group['work_days'])->pluck('day_name')->implode(', ') }} --}}
                   
                    {{ collect($group['work_days'] ?? [])->pluck('day_name')->implode(', ') }}

                    {{-- {{ implode(', ', array_column($group['work_days'] ?? [], 'day_name')) }} --}}
                </p>
            </div>
            <div class="section details"
                 style="text-align: right;">
                <p><strong>Flexible In Time:</strong> {{ $group['flexible_in_time'] }}</p>
                <p><strong>Flexible Out Time:</strong> {{ $group['flexible_out_time'] }}</p>
            </div>
        </div {{-- Employees Table --}} <div class="section">
        <h3>Employee List</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Profile ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Joining Date</th>
                    <th>Address</th>
                    <th>Division</th>
                    <th>District</th>
                    <th>Upazila</th>
                    <th>Company ID</th>
                    <th>Picture</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($group['employees']) --}}
                @foreach ($group['employees'] as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $employee['profile_id'] }}</td>
                        <td>{{ $employee['name'] }}</td>
                        <td>{{ $employee['mobile_number'] }}</td>
                        <td>{{ $employee['joining_date'] }}</td>
                        <td>{{ $employee['present_address'] }}</td>
                         <td>{{ $employee['division']['division_name_en'] ?? 'N/A' }}</td>
                        <td>{{ $employee['district']['district_name_en'] ?? 'N/A' }}</td>
                        <td>{{ $employee['upazila']['upazila_name_en'] ?? 'N/A' }}</td>
                        <td>{{ $employee['company_id'] }}</td>
                        <td>
                            @if (!empty($employee['picture']))
                                <img src="{{ asset('storage/' . $employee['picture']) }}"
                                     width="40"
                                     height="40"
                                     alt="pic">
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>

</body>

</html>
