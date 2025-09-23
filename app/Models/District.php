<?php

namespace App\Models;

use App\Traits\CreatedUId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Division;
use App\Models\Upazila;
class District extends Model
{
    use CreatedUId;
    
    protected $table = 'districts';
    
    protected $fillable = [
        'uid',
        'division_id',
        'district_name_en',
        'district_name_bn',
        'created_at'
    ];
    
    /**
     * Get the division that owns the district.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
    
    /**
     * Get the upazilas for the district.
     */
    public function upazilas()
    {
        return $this->hasMany(Upazila::class);
    }
    
    // Optional: Accessors for simpler attribute names
    public function getNameEnAttribute()
    {
        return $this->district_name_en;
    }
    
    public function getNameBnAttribute()
    {
        return $this->district_name_bn;
    }
}