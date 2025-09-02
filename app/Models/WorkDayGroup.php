<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WorkDayGroup extends Pivot
{
    protected $table = 'work_day_group';

    protected $fillable = ['group_id', 'work_day_id'];
}
