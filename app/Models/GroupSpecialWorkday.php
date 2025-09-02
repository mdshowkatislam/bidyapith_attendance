<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupSpecialWorkday extends Model
{
    protected $table = 'group_special_workdays';
    protected $fillable = ['group_id', 'special_workingday_id'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function specialWorkingDay()
    {
        return $this->belongsTo(SpecialWorkingday::class, 'special_workingday_id');
    }   
}