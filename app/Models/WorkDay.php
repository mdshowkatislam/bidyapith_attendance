<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkDay extends Model
{
    protected $table = "work_days";
    protected $fillable = ['day_name', 'is_weekend'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'work_day_group');
    }
    public function getDayNameAttribute($value)
{
    return ucfirst($value); // Always return with capital first letter
}

}
