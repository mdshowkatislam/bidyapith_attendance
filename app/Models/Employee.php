<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CheckInOut;
use App\Models\Section;
use App\Models\Department;
use App\Models\Division;

class Employee extends Model
{
    protected $table = "employees";

    protected $fillable = [
        'profile_id',
        'name',
        'father_name',
        'mother_name',
        'dob',
        'joining_date',
        'picture',
        'nid',
        'mobile_number',
        'present_address',
        'permanent_address',
        'division_id',
        'department_id',
        'section_id',
        'badgenumber',
        'company_id',
        'card_number',
        'status'
    ];

    // Relationship: Employee has many CheckInOut records
    public function checkInOut()
    {
        return $this->hasMany(CheckInOut::class, 'user_id', 'profile_id');
    }

    // Relationship: Employee belongs to a Section
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Relationship: Employee belongs to a Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relationship: Employee belongs to a Division
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'employee_group', 'employee_id', 'group_id');
    }
}
