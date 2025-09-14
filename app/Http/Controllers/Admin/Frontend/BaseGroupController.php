<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ShiftSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// use PDF;

class BaseGroupController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions(['verify' => false])
            ->get('http://attendance2.localhost.com/api/group_manage/list');

        $groups = $response->json()['groups'] ?? [];
        // dd(  $groups );

        return view('admin.frontend.group.index', compact('groups'));
    }

    public function add()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
            ->withOptions(['verify' => false])
            ->get('http://attendance2.localhost.com/api/group_manage/add');
        // dd($response->json());

        $message = $response->json()['message'] ?? 'success';

        if ($message === 'no_employees') {
            return redirect()->route('error-page')->with('error', 'No employees available.');
        }

        if ($message === 'no_workdays_or_shifts') {
            return redirect()->route('error-page')->with('error', 'Work days or shift settings are missing.');
        }

        $workDays = $response->json()['workDays'] ?? [];
        $employees = $response->json()['employees'] ?? [];
        $shifts = $response->json()['shifts'] ?? [];
        $branches = $response->json()['branches'] ?? [];

        return view('admin.frontend.group.create', compact('workDays', 'employees', 'shifts', 'branches'));
    }

    private function getGroupData($id)
    {
        $response = Http::withOptions(['verify' => false])
            ->get("http://attendance2.localhost.com/api/group_manage/details/{$id}");

        return $response->json()['group'] ?? null;
    }

    public function previewPdfView($id)
    {
        $group = $this->getGroupData($id);
        // dd($group);
        if (!$group) {
            return redirect()->route('group_manage.index')->withErrors('Group not found.');
        }

        return view('admin.frontend.group.pdf', compact('group'));
    }

    public function downloadGroupPdf($id)
    {
        $group = $this->getGroupData($id);

        if (!$group) {
            return redirect()->route('group_manage.index')->withErrors('Group not found.');
        }

        $pdf = PDF::loadView('admin.frontend.group.pdf', compact('group'));
        return $pdf->download('group-details.pdf');
    }

    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'day_name' => 'required|string|unique:work_days,day_name',
        //     'is_weekend' => 'required|boolean',
        // ]);

        $response = Http::withOptions(['verify' => false])
            ->post('http://attendance2.localhost.com/api/group_manage/store', $request->all());
        // dd($response->json()['status']);
        // \Log::info('Group Store Response:', $response->json());

        return redirect()->route('group_manage.index')->with('success', 'Group created successfully.');
    }

    public function edit($id)
    {
        $response = Http::withOptions(['verify' => false])
            ->get("http://attendance2.localhost.com/api/group_manage/edit/{$id}");

        $group = $response->json()['group'] ?? null;

        $employees = $response->json()['employees'] ?? null;
        $workDays = $response->json()['workDays'] ?? null;
        $shifts = $response->json()['shifts'] ?? null;
        $branches = $response->json()['branches'] ?? null;
        // dd($shifts);

        return view('admin.frontend.group.edit', compact('group', 'shifts', 'employees', 'workDays', 'branches'));
    }

 public function update($id, Request $request)
{
    try {
        $response = Http::withOptions(['verify' => false])
            ->post("http://attendance2.localhost.com/api/group_manage/update/{$id}", $request->all());

        $data = $response->json();

        if (!$data || ($data['status'] ?? false) === false) {
            return response()->json([
                'status' => false,
                'message' => $data['message'] ?? 'Group update failed.',
                'errors' => $data['errors'] ?? null
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => $data['message'] ?? 'Group updated successfully.'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}


    public function destroy($id)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->delete("http://attendance2.localhost.com/api/group_manage/delete/{$id}");

            if ($response->successful()) {
                return redirect()->route('group_manage.index')->with('success', $response['message']);
            } else {
                return redirect()->route('group_manage.index')->with('error', $response['error'] ?? 'Failed to delete.');
            }
        } catch (\Exception $e) {
            \Log::error('API call failed: ' . $e->getMessage());
            return redirect()->route('group_manage.index')->with('error', 'Something went wrong while deleting.');
        }
    }
}
