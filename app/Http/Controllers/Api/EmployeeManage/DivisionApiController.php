<?php

namespace App\Http\Controllers\Api\EmployeeManage ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use Illuminate\Support\Facades\Log;

class DivisionApiController extends Controller
{
    public function index()
    {
       
        try {
            $divisions = Division::latest()->get();
            
            return response()->json([
                'status' => true,
                'data' => $divisions,
                'message' => 'Divisions retrieved successfully.'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve divisions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $division = Division::findOrFail($id);
            
            return response()->json([
                'status' => true,
                'data' => $division,
                'message' => 'Division retrieved successfully.'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Division not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'division_name_en' => 'required|string|max:60|unique:divisions,division_name_en',
                'division_name_bn' => 'nullable|string|max:60|unique:divisions,division_name_bn',
            ]);

            $division = Division::create($request->all());

            return response()->json([
                'status' => true,
                'data' => $division,
                'message' => 'Division created successfully.'
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
                'message' => 'Failed to create division.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $division = Division::findOrFail($id);

            $request->validate([
                'division_name_en' => 'required|string|max:60|unique:divisions,division_name_en,' . $division->id,
                'division_name_bn' => 'nullable|string|max:60|unique:divisions,division_name_bn,' . $division->id,
            ]);

            $division->update([
                'division_name_en' => $request->division_name_en,
                'division_name_bn' => $request->division_name_bn,
            ]);

            return response()->json([
                'status' => true,
                'data' => $division,
                'message' => 'Division updated successfully.'
            ], 200);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update division.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $division = Division::findOrFail($id);
            $division->delete();

            return response()->json([
                'status' => true,
                'message' => 'Division deleted successfully.'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete division.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}