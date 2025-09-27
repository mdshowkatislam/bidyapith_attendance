<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedUId;

class Designation extends Model
{
   use CreatedUId;
    
    protected $table = 'designations';
    
    protected $fillable = [
        'uid',
        'designation_name',
    
    ];
}

