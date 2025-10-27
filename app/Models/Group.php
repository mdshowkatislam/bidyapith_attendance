<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\FlexibleTimeGroup;
use App\Models\SpecialWorkingday;
use App\Models\WorkDay;
use App\Services\ExternalDataService;
use Illuminate\Database\Eloquent\Model;

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

   public function employees()
{
    return $this->belongsToMany(
        Employee::class,
        'employee_group',
        'group_id',
        'employee_emp_id',
        'id',
        'profile_id'
    )->withPivot('created_at', 'updated_at');
}

    /**
     * ✅ Fetch branch data via external API (from config/api_url.php)
     */
    public function getBranchDetailsAttribute()
    {
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
     * ✅ Other Relationships
     */
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

    public function getWorkdayNamesAttribute()
    {
        return $this->workDays->pluck('name')->implode(', ');
    }

    public function getShiftNameAttribute()
    {
        return $this->shift_data['shift_name_en'] ?? 'N/A';
    }

    public function getBranchNameAttribute()
    {
        return $this->branch_data['branch_name_en'] ?? 'N/A';
    }
}