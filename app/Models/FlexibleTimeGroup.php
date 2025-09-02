<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlexibleTimeGroup extends Model
{
    protected $table = 'flexible_time_groups';

    protected $fillable = [
        'group_id',
        'flexible_in_time',
        'flexible_out_time',
        'day_name',
        'description',
        'status',
    ];
    public function group(){
        return $this->belongsTo(Group::class);
    }
}

           
