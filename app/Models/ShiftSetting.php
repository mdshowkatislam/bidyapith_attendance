<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUId;

/**
 * ShiftSetting Model 
 * @property string $uid
 * @property int $branch_id
 * @property string $shift_name_en
 * @property string $shift_name_bn
 * @property string $start_time
 * @property string $end_time
 * @property string|null $description
 * @property int $eiin
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\Branch $branch
 */

class ShiftSetting extends Model
{ 
    use CreatedUId;
    protected $table = "shift_settings";
    protected $fillable = [
        'uid',
        'branch_code',
        'shift_name_en',
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
        return $this->belongsTo(Branch::class, 'branch_code', 'branch_code')
        ->select('id','uid','branch_code','branch_name_en','branch_name_bn','head_of_branch_id');
    }
}



