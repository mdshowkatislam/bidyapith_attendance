<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\Models\FlexibleTimeGroup;
use App\Models\SpecialWorkingday;
use App\Models\WorkDay;
use App\Services\ExternalDataService;

class Group extends Model
{
    protected $table = 'groups';

    protected $fillable = [
        'group_name',
        'description',
        'branch_uid',
        'shift_uid',
        'status',
        'flexible_in_time',
        'flexible_out_time'
    ];



    /**
     * ✅ Fetch branch data via external API (from config/api_url.php)
     */


  public function getBranchDetailsAttribute()
    {
        // Don't call statically - use service container
        $externalDataService = app(ExternalDataService::class);
        return $externalDataService->fetchBranchDetails($this->branch_uid);
    }

    /**
     * Get shift details relationship
     */
    public function getShiftDetailsAttribute()
    {
        $externalDataService = app(ExternalDataService::class);
        return $externalDataService->fetchShiftDetails($this->shift_uid);
    }

    /**
     * ✅ Relationships (only relevant ones kept)
     */

  public function employees()
{
    return $this->belongsToMany(Employee::class, 'employee_group', 'group_id', 'employee_emp_id', 'id', 'profile_id');
}

    public function workDays()
    {
        return $this->belongsToMany(WorkDay::class, 'work_day_group');
    }

    public function flexibleTime()
    {
        return $this->hasOne(FlexibleTimeGroup::class, 'group_id');
    }

    public function specialWorkdays()
    {
        return $this->belongsToMany(SpecialWorkingday::class, 'group_special_workdays');
    }

    /**
     * ✅ Accessors
     */

    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    // Workday names (local relationship)
    public function getWorkdayNamesAttribute()
    {
        return $this->workDays->pluck('name')->implode(', ');
    }

    // ✅ Use external data for display-friendly names
    public function getShiftNameAttribute()
    {
        return $this->shift_data['shift_name_en'] ?? 'N/A';
    }

    public function getBranchNameAttribute()
    {
        return $this->branch_data['branch_name_en'] ?? 'N/A';
    }
}
