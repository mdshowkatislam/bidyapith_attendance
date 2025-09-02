<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;use App\Models\Department;
use App\Models\Section;

class SectionController extends Controller
{
     public function index()
    {
        $sections = Section::with('department')->latest()->get();
        return view('admin.section.index', compact('sections'));
    }

    public function add($id = null)
    {
        $section = $id ? Section::findOrFail($id) : null;
        $departments = Department::all();
        return view('admin.section.form', compact('section', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name',
            'department_id' => 'required|exists:departments,id',
        ]);

        Section::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('section.list')->with('success', 'Section created successfully.');
    }

    public function edit($id)
    {
        $section = Section::findOrFail($id);
        $departments = Department::all();
        return view('admin.section.form', compact('section', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:sections,name,' . $section->id,
            'department_id' => 'required|exists:departments,id',
        ]);

        $section->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('section.list')->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $attendance_status) // Rename var for clarity
    {
        $attendance_status->delete();
        return redirect()->route('section.list')->with('success', 'Section deleted successfully.');
    }
}
