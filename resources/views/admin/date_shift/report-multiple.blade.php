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
            font-size: 12px;
            margin: 10px;
            color: #333;
        }
        #download-pdf-btn:hover {
            background-color: white !important;
            color: #4CAF50 !important;
            border: 1px solid #4CAF50 !important;
        }


        .container {
            width: 100%;
        }



        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #6aebb9;
            text-align: center;
            vertical-align: middle;
            padding: 0px;
            word-wrap: break-word;
        }

        /* Rotate date headers */
        th.rotate {
            height: 40px;
            /* height of header */
            padding: 0;
            position: relative;
            vertical-align: bottom;
            text-align: center;
        }

        th.rotate>div {
            transform: rotate(-90deg);
            /* bottom -> top */
            transform-origin: left bottom;
            position: absolute;
            bottom: 5px;
            /* adjust vertical placement */
            left: 50%;
            /* center horizontally */
            white-space: nowrap;
            text-align: center;
        }


        thead {
            background-color: #4CAF50;
            color: white;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #f2f2f7;
        }

        tbody tr:hover {
            background-color: #aef7be;
        }

        td.status-cell {

            padding: 0px;
            border-radius: 3px;
        }

        th:first-child,
        td:first-child {
            width: 40px;
            /* ID */
        }

        th:nth-child(2),
        td:nth-child(2) {
            width: 150px;
            /* Employee Name */
        }

        th:last-child,
        td:last-child {
            width: auto;
            /* Total Days */
        }
        .container {
            max-width: 1440px !important;
        }
    </style>
</head>

<body>
    <div class="container">
    @php
        $alldate = [];
        foreach ($data as $item) {
            $alldate[] = $item['date'];
        }

        // Mapping for status_sms to short code
        $statusMap = [
            'Present' => 'P',
            'Absent' => 'A',
            'Late' => 'L',
            'Left Early' => 'LE',
            'Late & Left Early' => 'LLE',
        ];

        // Color mapping for short codes
        $colorMap = [
            'P' => 'background-color: rgb(159, 235, 159); color: #155724; font-weight: bold;', // green
            'A' => 'background-color: gray; color: white; font-weight: bold;', // red
            'L' => 'background-color: #fff3cd; color: #856404; font-weight: bold;', // orange
            'LE' => 'background-color: #cce5ff; color: #004085; font-weight: bold;', // blue
            'LLE' => 'background-color: #fa7d7d; color: #4b0082; font-weight: bold;', // purple
        ];

        // Group attendance by employee name
        $employees = [];

        foreach ($data as $dayData) {
            foreach ($dayData['attendance'] as $att) {
                // dd( $att);
                $empName = $att['name'];
                $employees[$empName]['records'][$dayData['date']] = $statusMap[$att['status_sms']] ?? '';
                $employees[$empName]['group_name'] = $att['group_name'] ?? '';
                $employees[$empName]['division'] = $att['division'] ?? '';
                $employees[$empName]['department'] = $att['department'] ?? '';
                $employees[$empName]['section'] = $att['section'] ?? '';
            }
        }
    @endphp

    <div style="display:flex; justify-content:space-between;  margin-bottom:10px;margin-top:10px; color:#4CAF50">
        <h2>Employee Attendance Report</h2>
        <button class="btn btn-success"
                id="download-pdf-btn">
            Download PDF
        </button> 
    </div>
    <div class="pl-4 pt-2">
        <p style="margin-left: 8px;margin-right: 4px; font-size: 16px;">From: {{ reset($alldate) }} — To: {{ end($alldate) }}</p>
    </div>


    {{-- Legend Row --}}
    <div class="d-flex justify-content-center mt-2">


        <div style="margin-bottom: 10px; border:1px solid #9FEB9F; border-radius:5px; padding:3px;">
            <span style="{{ $colorMap['P'] }}">P</span> = Present ||
            <span style="{{ $colorMap['A'] }}">A</span> = Absent ||
            <span style="{{ $colorMap['L'] }}">L</span> = Late ||
            <span style="{{ $colorMap['LE'] }}">LE</span> = Left Early ||
            <span style="{{ $colorMap['LLE'] }}">LLE</span> = Late & Left Early
        </div>

    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                @foreach ($alldate as $date)
                    <th class="rotate">
                        <div>{{ \Carbon\Carbon::parse($date)->format('d-M') }}</div>
                    </th>
                @endforeach

            </tr>
        </thead>
        <tbody>
            @php $serial = 1; @endphp
            @foreach ($employees as $empName => $data)
                <tr>
                    <td>{{ $serial++ }}</td>
                    <td>{{ $empName }}</td>
                    @foreach ($alldate as $date)
                        @php
                            $status = $data['records'][$date] ?? '';
                            $style = $colorMap[$status] ?? '';
                            $status = $data['records'][$date] ?? '';
                            $displayStatus = $status ?: 'A';
                            $style = $colorMap[$displayStatus] ?? '';
                        @endphp

                        <td style="{{ $style }}">{{ $displayStatus }}</td>
                    @endforeach

                </tr>
                <tr style="background-color: #f9f9f9;font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif">
                    <td colspan="2">Employee Statistics :</td>
                    <td colspan="{{ count($alldate) }}" style="text-align: left;padding-left:4px;">
                        <strong>total_present:</strong> {{ collect($data['records'])->filter(fn($s) => $s === 'P')->count() }},
                        @php
                            $totalAbsent = 0;
                            foreach ($alldate as $date) {
                                $status = $data['records'][$date] ?? '';
                                $displayStatus = $status ?: 'A';
                                if ($displayStatus === 'A') {
                                    $totalAbsent++;
                                }
                            }
                        @endphp
                        <strong>total_absent:</strong> {{ $totalAbsent }}, group: {{ $data['group_name'] }},
                      <strong>  division:</strong> {{ $data['division'] }},
                       <strong> department:</strong> {{ $data['department'] }},
                       <strong> section:</strong> {{ $data['section'] }}
                    </td>

                </tr>
            @endforeach
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
