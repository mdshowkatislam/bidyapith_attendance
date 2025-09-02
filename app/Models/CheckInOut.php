<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class CheckInOut extends Model
{
    protected $table = 'check_in_outs';

    protected $fillable = [ 'user_id','log_id','machine_id','date','in_time','out_time','status'];
    protected $casts = [
        'in_time'  => 'datetime:H:i:s',
        'out_time' => 'datetime:H:i:s',
    ];

    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class,'user_id','profile_id');
    // }

   

  

     
}
