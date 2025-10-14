<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\DropdownService;


class DateShiftAttendanceController extends Controller
{

     protected $dropdownService;

    public function __construct(DropdownService $dropdownService)
    {
        $this->dropdownService = $dropdownService;
    }
  public function index()
    {
        \Log::info('hi0');
        try {
            $data = $this->dropdownService->getAllDropdownData();

            return response()->json($data);
            
        } catch (\Exception $e) {
            \Log::error('Error in index method: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Additional endpoints for dependent dropdowns
    public function getShiftsByBranch($branchId)
    {
        try {
            $shifts = $this->dropdownService->getShiftsByBranch($branchId);
            return response()->json(['shifts' => $shifts]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch shifts'], 500);
        }
    }

    public function getDistrictsByDivision($divisionId)
    {
        try {
            $districts = $this->dropdownService->getDistrictsByDivision($divisionId);
            return response()->json(['districts' => $districts]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch districts'], 500);
        }
    }

    public function getUpazilasByDistrict($districtId)
    {
        try {
            $upazilas = $this->dropdownService->getUpazilasByDistrict($districtId);
            return response()->json(['upazilas' => $upazilas]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch upazilas'], 500);
        }
    }
}
