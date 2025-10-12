<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeGroup extends Pivot
{
    protected $table = 'employee_group';

    protected $fillable = ['group_id', 'employee_emp_id'];
}