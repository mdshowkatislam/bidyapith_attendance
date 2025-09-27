<?php


namespace App\Http\Controllers\Api\EmployeeManage ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Division;

class DistrictApiController extends Controller
{
    /**
     * Display a listing of the districts.
     */
    public function index()
    {
        try {
            $districts = District::with('division')->latest()->get();
            
            return response()->json([
              'status' => true,
                'data' => $districts,
                'message' => 'Districts retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
              'status' => false,
                'message' => 'Failed to retrieve districts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data needed for creating a district (divisions list).
     */
    public function createData()
    {
        try {
            $divisions = Division::all();
            
            return response()->json([
              'status' => true,
                'data' => [
                    'divisions' => $divisions
                ],
                'message' => 'Create data retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
              'status' => false,
                'message' => 'Failed to retrieve create data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created district.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'district_name_en' => 'required|string|max:255|unique:districts,district_name_en',
                'district_name_bn' => 'nullable|string|max:255|unique:districts,district_name_bn',
                'division_id' => 'required|exists:divisions,id',
            ]);

            $district = District::create([
                'district_name_en' => $request->district_name_en,
                'district_name_bn' => $request->district_name_bn,
                'division_id' => $request->division_id,
            ]);

            return response()->json([
              'status' => true,
                'data' => $district->load('division'),
                'message' => 'District created successfully.'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
              'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
              'status' => false,
                'message' => 'Failed to create district.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified district.
     */
    public function show($id)
    {
        try {
            $district = District::with('division')->findOrFail($id);
            
            return response()->json([
              'status' => true,
                'data' => $district,
                'message' => 'District retrieved successfully.'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
              'status' => false,
                'message' => 'District not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
              'status' => false,
                'message' => 'Failed to retrieve district.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified district.
     */
    public function update(Request $request, $id)
    {
        try {
            $district = District::findOrFail($id);

            $request->validate([
                'district_name_en' => 'required|string|max:255|unique:districts,district_name_en,' . $district->id,
                'district_name_bn' => 'nullable|string|max:255|unique:districts,district_name_bn,' . $district->id,
                'division_id' => 'required|exists:divisions,id',
            ]);

            $district->update([
                'district_name_en' => $request->district_name_en,
                'district_name_bn' => $request->district_name_bn,
                'division_id' => $request->division_id,
            ]);

            return response()->json([
              'status' => true,
                'data' => $district->load('division'),
                'message' => 'District updated successfully.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
              'status' => false,
                'message' => 'District not found.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
              'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
              'status' => false,
                'message' => 'Failed to update district.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified district.
     */
    public function destroy($id)
    {
        try {
            $district = District::findOrFail($id);
            $district->delete();

            return response()->json([
              'status' => true,
                'message' => 'District deleted successfully.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
              'status' => false,
                'message' => 'District not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
              'status' => false,
                'message' => 'Failed to delete district.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}