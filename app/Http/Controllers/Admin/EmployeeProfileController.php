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

class EmployeeProfileController extends Controller
{
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
        
        $query = Employee::with(['division', 'district', 'upazila']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
             dd($request->search);
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('badgenumber', 'like', "%{$search}%")
                    ->orWhere('nid', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%")
                    ->orWhere('nid', 'like', "%{$search}%");
            });
        }

        // Filter by division
        if ($request->has('division_id') && $request->division_id != '') {
           
            $query->where('division_id', $request->division_id);
        }

        // Filter by district
        if ($request->has('district_id') && $request->district_id != '') {
            $query->where('district_id', $request->district_id);
        }

        // Filter by upazila
        if ($request->has('upazila_id') && $request->upazila_id != '') {
            $query->where('upazila_id', $request->upazila_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $employees = $query->paginate(5);
        $divisions = Division::all();
        $districts = District::all();
        $upazilas = Upazila::all();
        // dd( $employees->toArray() );

        return view('admin.employee_profiles.index', compact('employees', 'divisions', 'districts', 'upazilas'));
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
