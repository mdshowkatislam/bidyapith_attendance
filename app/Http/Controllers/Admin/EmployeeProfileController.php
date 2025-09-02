<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        // Keyword search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%")
                    ->orWhere('badgenumber', 'like', "%{$search}%")
                    ->orWhere('nid', 'like', "%{$search}%");
            });
        }

        // Filter by Division, Department, Section
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        // Get the employees with relationships
        $employees = $query
            ->with(['division', 'department', 'section'])
            ->orderBy('name')
            ->paginate(15)
            ->appends($request->query());  // Maintain filters in pagination links

        // For dropdown filters
        $divisions = Division::all();
        $departments = Department::all();
        $sections = Section::all();

        return view('admin.employee_profiles.index', compact('employees', 'divisions', 'departments', 'sections'));
    }

    public function add()
    {
        $divisions = Division::all();
        $departments = Department::all();
        $sections = Section::all();
        return view('admin.employee_profiles.add', ['divisions' => $divisions, 'departments' => $departments, 'sections' => $sections]);
    }

    public function store(Request $request)
    {
        // dd($request->toArray());
        
        $validated = $request->validate([
            'profile_id' => 'required|integer|unique:employees,profile_id',
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'nid' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'division_id' => 'required|exists:divisions,id',
            'department_id' => 'required|exists:departments,id',
            'section_id' => 'required|exists:sections,id',
            'badgenumber' => 'nullable|string|max:255',
            'company_id' => 'nullable|string|max:255',
            'card_number' => 'nullable|string|max:255',
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
        $departments = Department::all();
        $sections = Section::all();
        $employee = Employee::findOrFail($id);
        return view('admin.employee_profiles.edit', compact('employee', 'divisions', 'departments', 'sections'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->toArray());
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'profile_id' => ['required', 'integer', Rule::unique('employees', 'profile_id')->ignore($employee->id)],
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'nid' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'division_id' => 'required|exists:divisions,id',
            'department_id' => 'required|exists:departments,id',
            'section_id' => 'required|exists:sections,id',
            'badgenumber' => 'nullable|string|max:255',
            'company_id' => 'nullable|string|max:255',
            'card_number' => 'nullable|string|max:255',
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

    public function getDepartments(Request $request)
    {
        $divisionId = $request->division_id;

        $departments = \App\Models\Department::where('division_id', $divisionId)->get();

        return response()->json($departments);
        
    }

    public function getSections(Request $request)
    {
        $departmentId = $request->department_id;
        $sections = Section::where('department_id', $departmentId)->get();

        return response()->json($sections);
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->status = !$employee->status;
        $employee->save();

        return redirect()->back()->with('success', 'Employee status updated successfully.');
    }
}
