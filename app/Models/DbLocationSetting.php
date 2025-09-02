<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DbLocationSetting extends Model
{
    
    protected $table = 'db_location_settings';

    protected $fillable = [
        'key',
        'value',
        'db_location'
    ];

    public $timestamps = true;

}
