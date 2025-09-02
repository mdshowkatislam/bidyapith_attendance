<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceStatusRequest;
use App\Http\Requests\UpdateAttendanceStatusRequest;
use App\Models\AttendanceStatus;
use Illuminate\Http\Request;

class AttendanceStatusController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => AttendanceStatus::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:attendance_statuses,name',
            'short_form' => 'required|string|max:5|unique:attendance_statuses,short_form',
            'status' => 'required|in:0,1',
        ]);

        $attendance_status = AttendanceStatus::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Attendance status created successfully.',
            'data' => $attendance_status,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $attendance_status = AttendanceStatus::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|unique:attendance_statuses,name,' . $id,
            'short_form' => 'required|string|max:5|unique:attendance_statuses,short_form,' . $id,
            'status' => 'required|in:0,1',
        ]);

        $attendance_status->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Attendance status updated successfully.',
            'data' => $attendance_status,
        ]);
    }

    public function show(AttendanceStatus $attendance_status)
    {
        return response()->json([
            'success' => true,
            'data' => $attendance_status,
        ]);
    }

    public function destroy(AttendanceStatus $attendance_status)
    {
        $attendance_status->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance status deleted successfully.',
        ]);
    }
}
