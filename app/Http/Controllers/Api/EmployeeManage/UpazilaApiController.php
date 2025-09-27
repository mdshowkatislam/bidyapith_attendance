<?php

namespace App\Http\Controllers\Api\EmployeeManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Upazila;

class UpazilaApiController extends Controller
{
    /**
     * Display a listing of the upazilas.
     */
    public function index()
    {
        try {
            $upazilas = Upazila::with('district')->latest()->get();
            
            return response()->json([
                'status'=> true,
                'data' => $upazilas,
                'message' => 'Upazilas retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to retrieve upazilas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data needed for creating an upazila (districts list).
     */
    public function createData()
    {
        try {
            $districts = District::all();
            
            return response()->json([
                'status'=> true,
                'data' => [
                    'districts' => $districts
                ],
                'message' => 'Create data retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to retrieve create data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created upazila.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'upazila_name_en' => 'required|string|max:255|unique:upazilas,upazila_name_en',
                'upazila_name_bn' => 'nullable|string|max:255|unique:upazilas,upazila_name_bn',
                'district_id' => 'required|exists:districts,id',
            ]);

            $upazila = Upazila::create([
                'upazila_name_en' => $request->upazila_name_en,
                'upazila_name_bn' => $request->upazila_name_bn,
                'district_id' => $request->district_id,
            ]);

            return response()->json([
                'status'=> true,
                'data' => $upazila->load('district'),
                'message' => 'Upazila created successfully.'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to create upazila.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified upazila.
     */
    public function show($id)
    {
        try {
            $upazila = Upazila::with('district')->findOrFail($id);
            
            return response()->json([
                'status'=> true,
                'data' => $upazila,
                'message' => 'Upazila retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Upazila not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to retrieve upazila.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified upazila.
     */
    public function update(Request $request, $id)
    {
        try {
            $upazila = Upazila::findOrFail($id);

            $request->validate([
                'upazila_name_en' => 'required|string|max:255|unique:upazilas,upazila_name_en,' . $upazila->id,
                'upazila_name_bn' => 'nullable|string|max:255|unique:upazilas,upazila_name_bn,' . $upazila->id,
                'district_id' => 'required|exists:districts,id',
            ]);

            $upazila->update([
                'upazila_name_en' => $request->upazila_name_en,
                'upazila_name_bn' => $request->upazila_name_bn,
                'district_id' => $request->district_id,
            ]);

            return response()->json([
                'status'=> true,
                'data' => $upazila->load('district'),
                'message' => 'Upazila updated successfully.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Upazila not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to update upazila.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified upazila.
     */
    public function destroy($id)
    {
        try {
            $upazila = Upazila::findOrFail($id);
            $upazila->delete();

            return response()->json([
                'status'=> true,
                'message' => 'Upazila deleted successfully.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Upazila not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'=> false,
                'message' => 'Failed to delete upazila.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}