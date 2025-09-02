<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialWorkingday extends Model
{ 
    protected $table = "special_working_days";
    protected $primaryKey = 'id'; 
    protected $fillable = ['id','date', 'day_type', 'description','status'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_special_workdays');
    }
}