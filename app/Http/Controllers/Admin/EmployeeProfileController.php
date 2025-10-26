<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Services\ExternalDataService;
use Illuminate\Support\Facades\Log;

class EmployeeProfileController extends Controller
{
     protected $externalDataService;

    public function __construct(ExternalDataService $externalDataService)
    {
        $this->externalDataService = $externalDataService;
    }

    // public function index(Request $request)
    // {
    //     $query = Employee::query();

    //     // Keyword search
    //     if ($request->filled('search')) {
    //         $search = $request->input('search');
    //         $query->where(function ($q) use ($search) {
    //             $q
    //                 ->where('name', 'like', "%{$search}%")
    //                 ->orWhere('mobile_number', 'like', "%{$search}%")
    //                 ->orWhere('badgenumber', 'like', "%{$search}%")
    //                 ->orWhere('nid', 'like', "%{$search}%");
    //         });
    //     }

    //     // Filter by Division, district, Upazila
    //     if ($request->filled('division_id')) {
    //         $query->where('division_id', $request->division_id);
    //     }
    //     if ($request->filled('district_id')) {
    //         $query->where('district_id', $request->district_id);
    //     }
    //     if ($request->filled('upazila_id')) {
    //         $query->where('upazila_id', $request->upazila_id);
    //     }

    //     // Get the employees with relationships
    //     $employees = $query
    //         ->with(['division', 'district', 'upazila'])
    //         ->orderBy('name')
    //         ->paginate(15)
    //         ->appends($request->query());  // Maintain filters in pagination links

    //     // For dropdown filters
    //     $divisions = Division::all();
    //     $districts = District::all();
    //     $upazilas = Upazila::all();
    //     // dd( $employees );

    //     return view('admin.employee_profiles.index', compact('employees', 'divisions', 'districts', 'upazilas'));
    // }




   
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search functionality - search in local table first
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('profile_id', 'like', "%{$search}%")
                  ->orWhere('caid', 'like', "%{$search}%")
                  ->orWhere('eiin', 'like', "%{$search}%");
            });
        }

        // Filter by person_type
        if ($request->has('person_type') && $request->person_type != '') {
            $query->where('person_type', $request->person_type);
        }

        $employees = $query->paginate(5);

        // Enhance employees with external data
        $enhancedEmployees = $employees->getCollection()->map(function ($employee) {
            return $this->enhanceEmployeeWithExternalData($employee);
        });

        // Replace the collection with enhanced data
        $employees->setCollection($enhancedEmployees);

        // Apply additional filters on enhanced data
        if ($request->has('division_id') && $request->division_id != '') {
            $employees->setCollection(
                $employees->getCollection()->filter(function ($employee) use ($request) {
                    return $employee['division_id'] == $request->division_id;
                })
            );
        }

        if ($request->has('district_id') && $request->district_id != '') {
            $employees->setCollection(
                $employees->getCollection()->filter(function ($employee) use ($request) {
                    return $employee['district_id'] == $request->district_id;
                })
            );
        }

        if ($request->has('upazila_id') && $request->upazila_id != '') {
            $employees->setCollection(
                $employees->getCollection()->filter(function ($employee) use ($request) {
                    return $employee['upazila_id'] == $request->upazila_id;
                })
            );
        }

        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();

        return view('admin.employee_profiles.index', compact('employees', 'divisions', 'districts', 'upazilas'));
    }

    /**
     * Enhance employee data with external information
     */
    private function enhanceEmployeeWithExternalData($employee)
    {
        try {
            // Fetch external employee details
            $externalData = $this->externalDataService->fetchEmployeeDetails(
                $employee->person_type, 
                $employee->profile_id
            );

            // If external data is not available, return basic employee data
            if (empty($externalData)) {
                return [
                    'id' => $employee->id,
                    'eiin' => $employee->eiin,
                    'caid' => $employee->caid,
                    'profile_id' => $employee->profile_id,
                    'person_type' => $employee->person_type,
                    'person_type_text' => $this->getPersonTypeText($employee->person_type),
                    'name' => 'Data not available',
                    'designation' => null,
                    'mobile_number' => null,
                    'present_address' => null,
                    'picture' => null,
                    'division_id' => null,
                    'district_id' => null,
                    'upazila_id' => null,
                    'division_name' => null,
                    'district_name' => null,
                    'upazila_name' => null,
                    'created_at' => $employee->created_at,
                    'updated_at' => $employee->updated_at,
                ];
            }

            // Get location names
            $divisionName = isset($externalData['division_id']) ? 
                $this->externalDataService->fetchDivisionName($externalData['division_id']) : null;
            $districtName = isset($externalData['district_id']) ? 
                $this->externalDataService->fetchDistrictName($externalData['district_id']) : null;
            $upazilaName = isset($externalData['upazilla_id']) ? 
                $this->externalDataService->fetchUpazilaName($externalData['upazilla_id']) : null;

            return [
                'id' => $employee->id,
                'eiin' => $employee->eiin,
                'caid' => $employee->caid,
                'profile_id' => $employee->profile_id,
                'person_type' => $employee->person_type,
                'person_type_text' => $this->getPersonTypeText($employee->person_type),
                'name' => $externalData['name_en'] ?? $externalData['name'] ?? 'Unknown',
                'designation' => $externalData['designation'] ?? null,
                'mobile_number' => $externalData['mobile_no'] ?? null,
                'present_address' => $externalData['address'] ?? null,
                'picture' => $externalData['image'] ?? null,
                'division_id' => $externalData['division_id'] ?? null,
                'district_id' => $externalData['district_id'] ?? null,
                'upazila_id' => $externalData['upazilla_id'] ?? null,
                'division_name' => $divisionName,
                'district_name' => $districtName,
                'upazila_name' => $upazilaName,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
            ];

        } catch (\Exception $e) {
            Log::error('Error enhancing employee data: ' . $e->getMessage());
            
            return [
                'id' => $employee->id,
                'eiin' => $employee->eiin,
                'caid' => $employee->caid,
                'profile_id' => $employee->profile_id,
                'person_type' => $employee->person_type,
                'person_type_text' => $this->getPersonTypeText($employee->person_type),
                'name' => 'Error loading data',
                'designation' => null,
                'mobile_number' => null,
                'present_address' => null,
                'picture' => null,
                'division_id' => null,
                'district_id' => null,
                'upazila_id' => null,
                'division_name' => null,
                'district_name' => null,
                'upazila_name' => null,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
            ];
        }
    }

    /**
     * Get person type as text
     */
    private function getPersonTypeText($personType)
    {
        return match ($personType) {
            1 => 'Teacher',
            2 => 'Staff',
            3 => 'Student',
            default => 'Unknown',
        };
    }

    /**
     * Show individual employee details
     */
    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        $enhancedEmployee = $this->enhanceEmployeeWithExternalData($employee);

        return view('admin.employee_profiles.show', compact('enhancedEmployee'));
    }

    /**
     * Advanced search with external data
     */
    public function search(Request $request)
    {
        $query = Employee::query();

        // Basic search on local fields
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('profile_id', 'like', "%{$search}%")
                  ->orWhere('caid', 'like', "%{$search}%");
            });
        }

        // Filter by person type
        if ($request->has('person_type') && $request->person_type != '') {
            $query->where('person_type', $request->person_type);
        }

        $employees = $query->get();

        // Enhance with external data
        $enhancedEmployees = $employees->map(function ($employee) {
            return $this->enhanceEmployeeWithExternalData($employee);
        });

        // Apply external data filters
        if ($request->has('name') && $request->name != '') {
            $enhancedEmployees = $enhancedEmployees->filter(function ($employee) use ($request) {
                return stripos($employee['name'], $request->name) !== false;
            });
        }

        if ($request->has('designation') && $request->designation != '') {
            $enhancedEmployees = $enhancedEmployees->filter(function ($employee) use ($request) {
                return stripos($employee['designation'] ?? '', $request->designation) !== false;
            });
        }

        if ($request->has('mobile_number') && $request->mobile_number != '') {
            $enhancedEmployees = $enhancedEmployees->filter(function ($employee) use ($request) {
                return stripos($employee['mobile_number'] ?? '', $request->mobile_number) !== false;
            });
        }

        return response()->json([
            'employees' => $enhancedEmployees->values(),
            'total' => $enhancedEmployees->count()
        ]);
    }


    public function add()
    {
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        return view('admin.employee_profiles.add', ['divisions' => $divisions, 'districts' => $districts, 'upazilas' => $upazilas]);
    }

    public function store(Request $request)
    {
        // dd($request->toArray());

        $validated = $request->validate([
            'profile_id' => 'required|integer|unique:employees,profile_id',
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:60',
            'mother_name' => 'nullable|string|max:60',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'nid' => 'nullable|string|max:100',
            'mobile_number' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'badgenumber' => 'nullable|string|max:100',
            'company_id' => 'nullable|string|max:100',
            'card_number' => 'nullable|string|max:100',
            'status' => 'required|in:0,1',
            'picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('employee_pictures', 'public');
            $validated['picture'] = $path;
        }

        Employee::create($validated);

        return redirect()
            ->route('employee_profile.index')
            ->with('success', 'Employee profile created successfully.');
    }

    public function edit($id)
    {
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        $employee = Employee::findOrFail($id);
        return view('admin.employee_profiles.edit', compact('employee', 'divisions', 'districts', 'upazilas'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->toArray());
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'profile_id' => ['required', 'integer', Rule::unique('employees', 'profile_id')->ignore($employee->id)],
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:60',
            'mother_name' => 'nullable|string|max:60',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'nid' => 'nullable|string|max:100',
            'mobile_number' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'upazila_id' => 'required|exists:upazilas,id',
            'badgenumber' => 'nullable|string|max:100',
            'company_id' => 'nullable|string|max:100',
            'card_number' => 'nullable|string|max:100',
            'status' => 'required|in:0,1',
            'picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('picture')) {
            // delete old if exists
            if ($employee->picture && Storage::disk('public')->exists($employee->picture)) {
                Storage::disk('public')->delete($employee->picture);
            }
            $path = $request->file('picture')->store('employee_pictures', 'public');
            $validated['picture'] = $path;
        }

        $employee->update($validated);

        return redirect()
            ->route('employee_profile.index')
            ->with('success', 'Employee profile updated successfully.');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        if ($employee->picture && Storage::disk('public')->exists($employee->picture)) {
            Storage::disk('public')->delete($employee->picture);
        }

        $employee->delete();

        return redirect()
            ->route('employee_profile.index')
            ->with('success', 'Employee profile deleted successfully.');
    }

    public function getDistrictsByDivision($divisionId)
    {
        try {
            $districts = District::where('division_id', $divisionId)
                ->orderBy('id', 'asc')
                ->get(['id', 'district_name_en']);

            return response()->json($districts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load districts'], 500);
        }
    }

    /**
     * Get upazilas by district ID
     */
    public function getUpazilasByDistrict($districtId)
    {
        try {
            $upazilas = Upazila::where('district_id', $districtId)
                ->orderBy('id', 'asc')
                ->get(['id', 'upazila_name_en']);

            return response()->json($upazilas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load upazilas'], 500);
        }
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = !$employee->status;
        $employee->save();

        return redirect()->back()->with('success', 'Employee status updated successfully.');
    }
}
