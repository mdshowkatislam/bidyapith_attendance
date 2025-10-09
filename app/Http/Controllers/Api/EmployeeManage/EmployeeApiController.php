<?php

namespace App\Http\Controllers\Api\EmployeeManage ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class EmployeeApiController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Employee::all()
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Log incoming data for debugging (optional)
            Log::info('Incoming Employee API data:', $request->all());

            // ✅ Step 1: Validation
            $validated = $request->validate([
                'eiin'        => 'nullable|numeric',
                'caid'        => 'nullable|numeric|unique:employees,caid',
                'emp_id'      => 'required|numeric|unique:employees,profile_id',
                'entity_type' => 'required|in:teacher,staff,student',
                'action'      => 'required|in:create,update',
            ]);

            // ✅ Step 2: Convert entity_type to person_type
            $personType = $this->mapEntityTypeToPersonType($validated['entity_type']);

            // ✅ Step 3: Handle create action
            if ($validated['action'] === 'create') {
                $employee = Employee::create([
                    'eiin'        => $validated['eiin'] ?? null,
                    'caid'        => $validated['caid'] ?? null,
                    'profile_id'  => $validated['emp_id'],
                    'person_type' => $personType,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Employee created successfully',
                    'data' => $employee
                ], 201);
            }

            // ✅ Step 4: Handle update action
            if ($validated['action'] === 'update') {
                $employee = Employee::where('profile_id', $validated['emp_id'])->first();

                if (!$employee) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Employee not found for update'
                    ], 404);
                }

                $employee->update([
                    'eiin'        => $validated['eiin'] ?? $employee->eiin,
                    'caid'        => $validated['caid'] ?? $employee->caid,
                    'person_type' => $personType,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Employee updated successfully',
                    'data' => $employee
                ]);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing employee', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a specific employee.
     */
    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $employee
        ]);
    }

    /**
     * Update a specific employee.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $validated = $request->validate([
            'eiin'        => 'nullable|numeric',
            'caid'        => 'nullable|numeric|unique:employees,caid,' . $employee->id,
            'entity_type' => 'nullable|in:teacher,staff,student',
        ]);

        $updateData = [
            'eiin' => $validated['eiin'] ?? $employee->eiin,
            'caid' => $validated['caid'] ?? $employee->caid,
        ];

        if (!empty($validated['entity_type'])) {
            $updateData['person_type'] = $this->mapEntityTypeToPersonType($validated['entity_type']);
        }

        $employee->update($updateData);

        return response()->json([
            'status' => true,
            'message' => 'Employee updated successfully',
            'data' => $employee
        ]);
    }

    /**
     * Remove a specific employee.
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found'
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }

    /**
     * Convert entity_type string to person_type integer.
     */
  private function mapEntityTypeToPersonType(string $entityType): int
{
    return match ($entityType) {
        'teacher' => Employee::TYPE_TEACHER,
        'staff'   => Employee::TYPE_STAFF,
        'student' => Employee::TYPE_STUDENT,
        default   => abort(422, "Invalid entity_type: {$entityType}. Allowed: teacher, staff, student."),
    };
}
}
