<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CheckInOut;
use App\Models\Upazila;
use App\Models\District;
use App\Models\Division;

class Employee_old extends Model
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
        'district_id',
        'upazila_id',
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

    // Relationship: Employee belongs to a Upazila
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    // Relationship: Employee belongs to a Department
    public function district()
    {
        return $this->belongsTo(District::class);
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
