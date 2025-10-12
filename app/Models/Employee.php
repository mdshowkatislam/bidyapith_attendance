<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'profile_id',
        'eiin',
        'caid',
        'person_type'
    ];
// FOR BETTER USE IN CONTROLLER . ->where('person', Employee::TYPE_TEACHER )
    const TYPE_TEACHER = 1;
    const TYPE_STAFF = 2;
    const TYPE_STUDENT = 3;
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
    return $this->belongsToMany(Group::class, 'employee_group', 'employee_emp_id', 'group_id', 'profile_id', 'id');
}
}
