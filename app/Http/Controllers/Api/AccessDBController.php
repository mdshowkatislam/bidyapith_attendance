<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CheckInOut;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccessDBController extends Controller
{
    public function accessDBstore(Request $request)
    {
        // \Log::info($request);
       
        if (!$request->has('studentData')) {
            Log::error('No studentData provided in the request');
            return response()->json([
                'success' => false,
                'message' => 'studentData is required',
            ], 400);
        }

        $studentData = $request->input('studentData');

        if (!is_array($studentData) || count($studentData) === 0) {
            Log::info('Student data is empty');
            return response()->json([
                'success' => false,
                'message' => 'No student records found in studentData',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $inserted=[];

            foreach ($studentData as $student) {

                $date=isset($student['date']) ? Carbon::createFromFormat('Y-m-d',$student['date'])->format('Y-m-d') :null;

                $inTime=isset($student['in_time']) ? Carbon::createFromFormat('h:i A',$student['in_time'])->format('H:i') :null;
                $outTime=isset($student['out_time']) ? Carbon::createFromFormat('h:i A',$student['out_time'])->format('H:i'): null;

               $inserted[] = CheckInOut::create([
                    'user_id'     => $student['id'],
                    'log_id'      => $student['log_id'] ?? null,
                    'machine_id'  => $student['machine_id'] ?? null,
                    'date'        => $date,
                    'in_time'     => $inTime,
                    'out_time'    => $outTime,
                    'status'      => $student['status'] ?? 1,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All' .count($inserted).  'student check-in/out records have been inserted successfully.', 
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to insert student check-in/out records', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while inserting records.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
