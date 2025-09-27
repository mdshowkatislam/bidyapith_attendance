<?php

namespace App\Http\Controllers\Api\EmployeeManage ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DesignationApiController extends Controller
{
    /**
     * Display a listing of the designations.
     */
    public function index()
    {
        try {
            $designations = Designation::latest()->get();
            
            return response()->json([
                'status'=> true,
                'data' => $designations,
                'message' => 'Designations retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to retrieve designations.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created designation.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'designation_name' => 'required|string|max:60|unique:designations,designation_name',
            ]);

            DB::beginTransaction();

            // Generate unique uid
            $uid = $this->generateUniqueUid();

            $designation = Designation::create([
              
                'designation_name' => $request->designation_name,
            ]);

            DB::commit();

            return response()->json([
                'status'=> true,
                'data' => $designation,
                'message' => 'Designation created successfully.'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status'=> false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'=> false,
                'message' => 'Failed to create designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified designation.
     */
    public function show($id)
    {
        try {
            $designation = Designation::find($id);
            
            if (!$designation) {
                return response()->json([
                    'status'=> false,
                    'message' => 'Designation not found.'
                ], 404);
            }

            return response()->json([
                'status'=> true,
                'data' => $designation,
                'message' => 'Designation retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to retrieve designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified designation.
     */
    public function update(Request $request, $id)
    {
        try {
            $designation = Designation::find($id);

            if (!$designation) {
                return response()->json([
                    'status'=> false,
                    'message' => 'Designation not found.'
                ], 404);
            }

            $request->validate([
                'designation_name' => 'required|string|max:60|unique:designations,designation_name,' . $designation->id,
            ]);

            $designation->update([
                'designation_name' => $request->designation_name,
            ]);

            return response()->json([
                'status'=> true,
                'data' => $designation,
                'message' => 'Designation updated successfully.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to update designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified designation.
     */
    public function destroy($id)
    {
        try {
            $designation = Designation::find($id);

            if (!$designation) {
                return response()->json([
                    'status'=> false,
                    'message' => 'Designation not found.'
                ], 404);
            }

            $designation->delete();

            return response()->json([
                'status'=> true,
                'message' => 'Designation deleted successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to delete designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique uid for designation
     */
    private function generateUniqueUid()
    {
        do {
            // You can customize the uid generation logic as per your requirements
            $uid = rand(100000, 999999); // 6-digit random number
            $exists = Designation::where('uid', $uid)->exists();
        } while ($exists);

        return $uid;
    }

    /**
     * Alternative method to generate uid using timestamp (optional)
     */
    private function generateTimestampUid()
    {
        return (int) substr(time(), -6) + rand(100, 999);
    }
}