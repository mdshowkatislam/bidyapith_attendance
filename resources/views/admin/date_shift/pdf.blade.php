<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
            color: #333;
        }

        h2 {
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
            color: #2e7d32;
        }

        .container {
            width: 100%;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed; /* Allow custom column widths */
        }

        th, td {
             border: 1px solid #015736;
            padding: 8px 10px;
            text-align: center;
            word-wrap: break-word;
        }

        /* Wider Remark Column */
        th:last-child,
        td:last-child {
            width: 20%;
        }

        /* Header Styling */
        thead {
            background-color: #4CAF50;;
            color: white;
        }

        /* Zebra Striping */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
 
    </style>
</head>
<body>
    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Employee Attendance Report</h2>
        </div>

        <div class="mb-3" style="font-family:'Times New Roman', Times, serif;font-size:inherit">
            <strong>Date:</strong> {{ $date }} <br>
            <strong>Shift:</strong> {{ $shift_name }}
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:10%;">Name</th>
                    <th style="width:10%;">Group Name</th>
                    <th style="width:8%;">In Time</th>
                    <th style="width:8%;">Out Time</th>
                    <th style="width:8%;">Start Time</th>
                    <th style="width:8%;">End Time</th>
                    <th style="width:8%;">Flex In (min)</th>
                    <th style="width:8%;">Flex Out (min)</th>
                    <th style="width:8%;">Overtime</th>
                    <th style="width:8%;">Status</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendance as $index => $employee)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $employee['name'] }}</td>
                        <td>{{ $employee['group_name'] }}</td>
                        <td>{{ $employee['in_time'] ?? '-' }}</td>
                        <td>{{ $employee['out_time'] ?? '-' }}</td>
                        <td>{{ $employee['start_time'] ?? '-' }}</td>
                        <td>{{ $employee['end_time'] ?? '-' }}</td>
                        <td>{{ $employee['flexible_in_time'] ?? '0' }}</td>
                        <td>{{ $employee['flexible_out_time'] ?? '0' }}</td>
                        <td>{{ $employee['overtime'] }}</td>
                        <td>{{ $employee['status_sms'] }}</td>
                        <td>{{ $employee['remark'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">No attendance data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
