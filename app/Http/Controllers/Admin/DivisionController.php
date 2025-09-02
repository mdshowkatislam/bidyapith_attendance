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
            'name' => 'required|string|max:255|unique:divisions,name',
        ]);

        Division::create([
            'name' => $request->name,
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
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
        ]);

        $division->update([
            'name' => $request->name,
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
