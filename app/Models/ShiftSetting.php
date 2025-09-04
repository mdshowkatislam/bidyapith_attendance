<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUId;

/**
 * @property string $shift_name
 * @property string $id
 * @property string|null $desciption
 */


class ShiftSetting extends Model
{ 
    use CreatedUId;
    protected $table = "shift_settings";
    protected $fillable = [
        'uid',
        'branch_id',
        'shift_name',
        'shift_name_bn',
        'start_time',
        'end_time',
        'description',
        'eiin',
        'status',
          'created_at',
        'updated_at',
    ];  
     public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'uid')->select('uid', 'branch_name');
    }
}



