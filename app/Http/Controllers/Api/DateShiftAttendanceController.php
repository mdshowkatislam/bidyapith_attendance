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
       $districts=District::all();
       $upazilas=Upazila::all();
       $groups=Group::all();
       

        return response()->json([
                    'shifts' =>  $shifts,
                    'divisions' =>  $divisions,
                    'districts' =>  $districts,
                    'upazilas' =>  $upazilas,
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
        $upazilas = Upazila::where('district_id', $districtId)->get();

        return response()->json($upazilas);
    }
  


}
