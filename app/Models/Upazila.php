<?php

namespace App\Models;

use App\Models\District;
use App\Traits\CreatedUId;
use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    use CreatedUId;
    
    protected $table = 'upazilas';
    
    protected $fillable = [
        'uid',
        'district_id',
        'upazila_name_en',
        'upazila_name_bn',
        'created_at'
    ];
    
    // Optional: If you want simpler attribute names, you can use accessors
    public function getNameEnAttribute()
    {
        return $this->upazila_name_en;
    }
    
    public function getNameBnAttribute()
    {
        return $this->upazila_name_bn;
    }
    
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}