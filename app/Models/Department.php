<?php

namespace App\Models;
use App\Models\Division;
use App\Models\Section;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
     protected $table='departments';
     protected $fillable = ['name', 'division_id'];
    public function division(){
        return $this->belongsTo(Division::class);
    }
    public function section(){
        return $this->hasMany(Section::class);
    }
}
