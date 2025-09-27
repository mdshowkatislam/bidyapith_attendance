<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;use App\Models\District;
use App\Models\Upazila;

class UpazilaController extends Controller
{
     public function index()
    {
        $upazilas = Upazila::with('district')->latest()->get();
        return view('admin.upazila.index', compact('upazilas'));
    }

    public function add($id = null)
    {
        $upazila = $id ? Upazila::findOrFail($id) : null;
        $districts = District::all();
        return view('admin.upazila.form', compact('upazila', 'districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'upazila_name_en' => 'required|string|max:255|unique:upazilas,upazila_name_en',
            'upazila_name_bn' => 'nullable|string|max:255|unique:upazilas,upazila_name_bn',
            'district_id' => 'required|exists:districts,id',
        ]);

        Upazila::create([
            'upazila_name_en' => $request->upazila_name_en,
            'upazila_name_bn' => $request->upazila_name_bn,
            'district_id' => $request->district_id,
        ]);

        return redirect()->route('upazila.list')->with('success', 'Upazila created successfully.');
    }

    public function edit($id)
    {
        $upazila = Upazila::findOrFail($id);
        $districts = District::all();
        return view('admin.upazila.form', compact('upazila', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $upazila = Upazila::findOrFail($id);

        $request->validate([
            'upazila_name_en' => 'required|string|max:255|unique:upazilas,upazila_name_en,' . $upazila->id,
            'upazila_name_bn' => 'nullable|string|max:255|unique:upazilas,upazila_name_bn,' . $upazila->id,
            'district_id' => 'required|exists:districts,id',
        ]);

        $upazila->update([
            'upazila_name_en' => $request->upazila_name_en,
            'upazila_name_bn' => $request->upazila_name_bn,
            'district_id' => $request->district_id,
        ]);

        return redirect()->route('upazila.list')->with('success', 'Upazila updated successfully.');
    }

    public function destroy(Upazila $upazila) // Rename var for clarity
    {
        $upazila->delete();
        return redirect()->route('upazila.list')->with('success', 'Upazila deleted successfully.');
    }
}
