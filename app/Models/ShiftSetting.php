<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @property string $shift_name
 * @property string $id
 * @property string|null $desciption
 */


class ShiftSetting extends Model
{
    protected $table = "shift_settings";
    protected $fillable = [
       'shift_name',
       'start_time',
       'end_time',
       'description',
       'status',
    ];  
}

