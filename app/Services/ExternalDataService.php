<?php

namespace App\Services;

use App\Repositories\{
    BranchRepository,
    ShiftRepository,
    EmployeeRepository,
    DivisionRepository,
    DistrictRepository,
    UpazilaRepository,
    AttendanceRepository
};

class ExternalDataService
{
    protected $branchRepository;
    protected $shiftRepository;
    protected $employeeRepository;
    protected $divisionRepository;
    protected $districtRepository;
    protected $upazilaRepository;
    protected $attendanceRepository;

    public function __construct(
        BranchRepository $branchRepository,
        ShiftRepository $shiftRepository,
        EmployeeRepository $employeeRepository,
        DivisionRepository $divisionRepository,
        DistrictRepository $districtRepository,
        UpazilaRepository $upazilaRepository,
        AttendanceRepository $attendanceRepository
    ) {
        $this->branchRepository = $branchRepository;
        $this->shiftRepository = $shiftRepository;
        $this->employeeRepository = $employeeRepository;
        $this->divisionRepository = $divisionRepository;
        $this->districtRepository = $districtRepository;
        $this->upazilaRepository = $upazilaRepository;
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * Fetch employee details
     */
    public function fetchEmployeeDetails($personType, $profileId)
    {
        return $this->employeeRepository->getEmployeeDetails($personType, $profileId);
    }
 

    /**
     * Fetch shift details
     */
    public function fetchShiftDetails($shiftUid) 
    {
        return $this->shiftRepository->getShiftDetails($shiftUid);
    }

    /**
     * Fetch branch details
     */
    public function fetchBranchDetails($branchUid)
    {
        return $this->branchRepository->getBranchDetails($branchUid);
    }

    /**
     * Fetch division name
     */
    public function fetchDivisionName($divisionId)
    {
        $division = $this->divisionRepository->getDivisionDetails($divisionId);
        return $division['division_name_en'] ?? $division['division_name'] ?? $divisionId;
    }

    /**
     * Fetch district name
     */
    public function fetchDistrictName($districtId)
    {
        $district = $this->districtRepository->getDistrictDetails($districtId);
        return $district['district_name_en'] ?? $district['district_name'] ?? $districtId;
    }

    /**
     * Fetch upazila name
     */
    public function fetchUpazilaName($upazilaId)
    {
        $upazila = $this->upazilaRepository->getUpazilaDetails($upazilaId);
        return $upazila['upazila_name_en'] ?? $upazila['upazila_name'] ?? $upazilaId;
    }

    /**
     * Fetch attendance data
     */
    public function fetchAttendanceData(array $profileIds, $startDate, $endDate)
    {
        return $this->attendanceRepository->getAttendanceData($profileIds, $startDate, $endDate);
    }

    /**
     * Get all branches
     */
    public function getAllBranches()
    {
        return $this->branchRepository->getAllBranches();
    }

    /**
     * Get all divisions
     */
    public function getAllDivisions()
    {
        return $this->divisionRepository->getAll();
    }

    /**
     * Get districts by division
     */
    public function getDistrictsByDivision($divisionId)
    {
        return $this->districtRepository->getByDivision($divisionId);
    }

    /**
     * Get upazilas by district
     */
    public function getUpazilasByDistrict($districtId)
    {
        return $this->upazilaRepository->getByDistrict($districtId);
    }

    /**
     * Get shifts by branch
     */
    // public function getShiftsByBranch($branchUid)
    // {
    //     return $this->shiftRepository->branchUid($branchUid);
    // }

  public function fetchFilteredEmployees(array $filters = [])
    {
        $response = $this->employeeRepository->getFilteredEmployees($filters);
        return $response['data'] ?? [];
    }

}