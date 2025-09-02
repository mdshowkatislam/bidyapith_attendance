<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Division;

class DepartmentController extends Controller
{
   public function index()
    {
        $departments = Department::with('division')->latest()->get();
        return view('admin.department.index', compact('departments'));
    }

    public function add($id = null)
    {
        $department = $id ? Department::findOrFail($id) : null;
        $divisions = Division::all();
        return view('admin.department.form', compact('department', 'divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'division_id' => 'required|exists:divisions,id',
        ]);

        Department::create([
            'name' => $request->name,
            'division_id' => $request->division_id,
        ]);

        return redirect()->route('department.list')->with('success', 'Department created successfully.');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $divisions = Division::all();
        return view('admin.department.form', compact('department', 'divisions'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'division_id' => 'required|exists:divisions,id',
        ]);

        $department->update([
            'name' => $request->name,
            'division_id' => $request->division_id,
        ]);

        return redirect()->route('department.list')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $attendance_status) // <- change variable to $department for clarity
    {
        $attendance_status->delete();
        return redirect()->route('department.list')->with('success', 'Department deleted successfully.');
    }
}
