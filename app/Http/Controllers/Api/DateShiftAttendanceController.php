<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Department;
use App\Models\Section;
use App\Models\Group;
use App\Models\ShiftSetting;
use Illuminate\Http\Request;


class DateShiftAttendanceController extends Controller
{
  public function index(){
       $shifts=ShiftSetting::where('status',1)->get();
       $divisions=Division::all();
       $departments=Department::all();
       $sections=Section::all();
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

        $departments = Department::where('division_id', $divisionId)->get();

        return response()->json($departments);
    
    }

    public function getSections(Request $request)
    {
       
        $departmentId = $request->department_id;
        $sections = Section::where('department_id', $departmentId)->get();

        return response()->json($sections);
    }
  


}
