<?php

namespace App\Services;

use App\Repositories\BranchRepository;
use App\Repositories\ShiftRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\DivisionRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\UpazilaRepository;
use App\Models\Group;

class DropdownService
{
    protected $branchRepository;
    protected $shiftRepository;
    protected $employeeRepository;
    protected $divisionRepository;
    protected $districtRepository;
    protected $upazilaRepository;

    public function __construct(
        BranchRepository $branchRepository,
        ShiftRepository $shiftRepository,
        EmployeeRepository $employeeRepository,
        DivisionRepository $divisionRepository,
        DistrictRepository $districtRepository,
        UpazilaRepository $upazilaRepository
    ) {
        $this->branchRepository = $branchRepository;
        $this->shiftRepository = $shiftRepository;
        $this->employeeRepository = $employeeRepository;
        $this->divisionRepository = $divisionRepository;
        $this->districtRepository = $districtRepository;
        $this->upazilaRepository = $upazilaRepository;
    }

    public function getAllDropdownData(): array
    {
      
        return [
            'branches' => $this->branchRepository->getAll()->values()->toArray(),
            'shifts' => $this->shiftRepository->getAll()->values()->toArray(),
            'divisions' => $this->divisionRepository->getAll()->values()->toArray(),
            'districts' => $this->districtRepository->getAll()->values()->toArray(),
            'upazilas' => $this->upazilaRepository->getAll()->values()->toArray(),
            'groups' => Group::all()->toArray(),
        ];
    }
    public function getBranchShiftDropdownData(): array
    {
      
        return [
            'branches' => $this->branchRepository->getAll()->values()->toArray(),
            'shifts' => $this->shiftRepository->getAll()->values()->toArray(),
           
        ];
    }
    public function getBranchShiftGroupEmployeeDropdownData(): array
    {
      
        return [
            'branches' => $this->branchRepository->getAll()->values()->toArray(),
            'shifts' => $this->shiftRepository->getAll()->values()->toArray(),
            'employees' => $this->employeeRepository->getEmployeeDetails(),
            'groups' => Group::all()->toArray(),
           
        ];
    }

    public function getShiftsByBranch($branchUid): array
    {
        return $this->shiftRepository->getByBranch($branchUid)->toArray();
    }

    public function getDistrictsByDivision($divisionId): array
    {
        return $this->districtRepository->getByDivision($divisionId)->toArray();
    }

    public function getUpazilasByDistrict($districtId): array
    {
        return $this->upazilaRepository->getByDistrict($districtId)->toArray();
    }
}