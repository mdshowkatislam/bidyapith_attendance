<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    
    public function index()
    {
        $divisions = Division::latest()->get();
        return view('admin.division.index', compact('divisions'));
    }

    public function add($id = null)
    {
        $division = $id ? Division::findOrFail($id) : null;
        return view('admin.division.form', compact('division'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_name_en' => 'required|string|max:60|unique:divisions,division_name_en',
            'division_name_bn' => 'nullable|string|max:60|unique:divisions,division_name_bn',
        ]);

        Division::create([
            'division_name_en' => $request->division_name_en,
            'division_name_bn' => $request->division_name_bn,
        ]);

        return redirect()->route('division.list')->with('success', 'Division created successfully.');
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        return view('admin.division.form', compact('division'));
    }

    public function update(Request $request, $id)
    {
        $division = Division::findOrFail($id);

        $request->validate([
            'division_name_en' => 'required|string|max:60|unique:divisions,division_name_en,' . $division->id,
            'division_name_bn' => 'nullable|string|max:60|unique:divisions,division_name_bn,' . $division->id,
        ]);

        $division->update([
            'division_name_en' => $request->division_name_en,
            'division_name_bn' => $request->division_name_bn,
        ]);

        return redirect()->route('division.list')->with('success', 'Division updated successfully.');
    }
     public function destroy($id)
    {
        $selected_division = Division::findOrFail($id);
        $selected_division->delete();

        return redirect()->route('division.list')->with('success', 'Division deleted successfully.');
    }
}
