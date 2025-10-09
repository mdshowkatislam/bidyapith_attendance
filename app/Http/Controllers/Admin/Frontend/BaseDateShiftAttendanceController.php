<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BaseDateShiftAttendanceController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions(['verify' => false])
            ->get('http://attendance2.localhost.com/api/date-shift-wise-attendance');
        $json = $response->json();
   
        // dd($json);

        return view('admin.date_shift.index', [
            'shifts' => $json['shifts'] ?? null,
            'divisions' => $json['divisions'] ?? null,
            'districts' => $json['districts'] ?? null,
            'upazilas' => $json['upazilas'] ?? null,
            'groups' => $json['groups'] ?? null,
        ]);
    }

    public function reportGenarate(Request $request)
    {
        $queryParams = $request->all();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions(['verify' => false])
            ->get('http://attendance2.localhost.com/api/date/shift/attendance', $queryParams);

         

        if ($response->json()['type'] == 1) {
            $single_json = $response->json()['results'][0];
            //  dd( $single_json );
            return view('admin.date_shift.report', [
                'date' => $single_json['date'] ?? null,
                'shift_name' => $single_json['shift_name'] ?? null,
                'attendance' => $single_json['attendance'] ?? [],
                'status' => $single_json['status'] ?? null,
                'holiday_name' => $single_json[''] ?? null,
                'description' => $single_json['description'] ?? null,
                'shift_id' => 1,
            ]);
        }
        if ($response->json()['type'] == 2 || $response->json()['type'] == 3) {
            $multiple_json = $response->json()['results'];
            // dd(      $multiple_json );
            return view('admin.date_shift.report-multiple', [
               'data'=>$multiple_json 
            ]);
        }
    }
}
