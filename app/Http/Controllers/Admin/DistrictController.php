<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Division;

class DistrictController extends Controller
{
   public function index()
    {
        $districts = District::with('division')->latest()->get();
        return view('admin.district.index', compact('districts'));
    }

    public function add($id = null)
    {
        $district = $id ? District::findOrFail($id) : null;
        $divisions = Division::all();
        return view('admin.district.form', compact('district', 'divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'district_name_en' => 'required|string|max:255|unique:districts,district_name_en',
            'district_name_bn' => 'nullable|string|max:255|unique:districts,district_name_bn',
            'division_id' => 'required|exists:divisions,id',
        ]);

        District::create([
            'district_name_en' => $request->district_name_en,
            'district_name_bn' => $request->district_name_bn,
            'division_id' => $request->division_id,
        ]);

        return redirect()->route('district.list')->with('success', 'District created successfully.');
    }

    public function edit($id)
    {
        $district = District::findOrFail($id);
        $divisions = Division::all();
        return view('admin.district.form', compact('district', 'divisions'));
    }

    public function update(Request $request, $id)
    {
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

        return redirect()->route('district.list')->with('success', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();
        return redirect()->route('district.list')->with('success', 'District deleted successfully.');
    }
}
