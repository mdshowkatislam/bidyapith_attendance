<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Group;
use App\Models\ShiftSetting;
use Illuminate\Http\Request;


class DateShiftAttendanceController extends Controller
{
  public function index(){
       $shifts=ShiftSetting::where('status',1)->get();
       $divisions=Division::all();
       $departments=District::all();
       $sections=Upazila::all();
       $groups=Group::all();
       

        return response()->json([
                    'shifts' =>  $shifts,
                    'divisions' =>  $divisions,
                    'departments' =>  $departments,
                    'sections' =>  $sections,
                    'groups' =>  $groups,
                ]);
       
  }
    public function getDepartments(Request $request)
    { 
        $divisionId = $request->division_id;

        $district = District::where('division_id', $divisionId)->get();

        return response()->json($district);

    }

    public function getSections(Request $request)
    {
       
        $districtId = $request->district_id;
        $sections = Upazila::where('district_id', $districtId)->get();

        return response()->json($sections);
    }
  


}
