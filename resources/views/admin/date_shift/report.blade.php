<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
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
            table-layout: fixed;
            /* Allow custom column widths */
        }

        th,
        td {
            border: 2px solid #6aebb9;
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
            background-color: #4CAF50;
            color: white;
        }

        /* Zebra Striping */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Hover Effect (browser only) */
        tbody tr:hover {
            background-color: #d4edda;
        }

        .container {
            max-width: 1440px !important;
        }
    </style>
</head>

<body>
    {{-- @dd($attendance) --}}

    <div class="container ">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Employee Attendance Report</h2>
            <button class="btn btn-success"
                    id="download-pdf-btn">
                Download PDF
            </button>
        </div>

        <div class="d-flex justify-content-between">
            <div class="mb-3"
                 style="font-family:'Times New Roman', Times, serif;font-size:inherit">
                <strong>Date:</strong> {{ $date }} <br>
                <strong>Branch:</strong> {{ $branch_name }} <br>
                <strong>Shift:</strong> {{ $shift_name }} <br>
                <strong>Day Status:</strong> {{ $status }}
            </div>
            <div class="mb-3"
                 style="font-family:'Times New Roman', Times, serif;font-size:inherit">
                <strong>Holiday Name:</strong>
                @if ($holiday_name)
                    {{ $holiday_name }}
                @else
                    Not A Holiday
                @endif <br>
                <strong>Description:</strong>
                @if ($description)
                    {{ $description }}
                @else
                    No Description
                @endif

            </div>
        </div>


        <table>
            <thead>
                <tr>
                    <th style="width:5%;">#</th>
                    <th style="width:10%;">Name</th>
                    <th style="width:10%;">Group</th>
                    <th style="width:10%;">Division</th>
                    <th style="width:10%;">District</th>
                    <th style="width:10%;">Upazila</th>
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
                        <td>{{ $employee['division'] }}</td>
                        <td>{{ $employee['district'] }}</td>
                        <td>{{ $employee['upazila'] }}</td>
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
                        <td colspan="12"
                            class="text-center">No attendance data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        document.getElementById('download-pdf-btn').addEventListener('click', function() {
            // Hide the download button
            this.style.display = 'none';

            // Select the content to export
            const element = document.querySelector('.container');

            // PDF options
            const opt = {
                margin: 0.5,
                filename: 'attendance-report.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'landscape'
                }
            };

            // Generate the PDF
            html2pdf().set(opt).from(element).save().then(() => {
                // Show the button again after download
                document.getElementById('download-pdf-btn').style.display = 'inline-block';
            });
        });
    </script>
</body>

</html>
