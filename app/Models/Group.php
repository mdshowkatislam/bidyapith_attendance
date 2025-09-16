<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\FlexibleTimeGroup;
use App\Models\ShiftSetting;
use App\Models\SpecialWorkingday;
use App\Models\WorkDay;
use App\Models\Branch; // Add this import
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';
    protected $fillable = ['group_name', 'description', 'shift_id', 'status','flexible_in_time','flexible_out_time'];

    // Relationships

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_group', 'group_id', 'employee_id', 'id', 'id');
    }

    public function workDays()
    {
        return $this->belongsToMany(WorkDay::class, 'work_day_group');
    }

    public function shift()
    {
        return $this->belongsTo(ShiftSetting::class, 'shift_id');
    }
    
    // Add branch relationship through shift
  public function branch()
{
    return $this->hasOneThrough(
        Branch::class,          // Final model we want to access
        ShiftSetting::class,    // Intermediate model
        'id',                   // Foreign key on the ShiftSetting table (references Group's shift_id)
        'branch_code',          // Foreign key on the Branch table (references ShiftSetting's branch_code)
        'shift_id',             // Local key on the Group table
        'branch_code'           // Local key on the ShiftSetting table
    );
}

    public function flexibleTime()
    {
        return $this->hasOne(FlexibleTimeGroup::class, 'group_id');
    }

    public function specialWorkdays()
    {
        return $this->belongsToMany(SpecialWorkingday::class, 'group_special_workdays');
    }

    // Accessor for readable status
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    // Accessor for workday names as string
    public function getWorkdayNamesAttribute()
    {
        return $this->workDays->pluck('name')->implode(', ');
    }

    // Accessor for shift name
    public function getShiftNameAttribute()
    {
        return optional($this->shift)->shift_name_en ?? 'N/A';
    }

    // Accessor for branch name
    public function getBranchNameAttribute()
    {
        return optional($this->branch)->branch_name_en ?? 'N/A';
    }
}