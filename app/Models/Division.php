<?php

namespace App\Models;
use App\Models\District;
use App\Traits\CreatedUId;


use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
use CreatedUId;
    protected $table='divisions';
    protected $fillable=['uid','division_name_en','division_name_bn'];
    public function district(){
        return $this->hasMany(District::class);
    }
}
