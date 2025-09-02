<?php

namespace App\Models;
use App\Models\Division;
use App\Models\Department;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
      protected $table='sections';
      protected $fillable=['name','department_id'];
   
    public function department(){
        return $this->belongsTo(Department::class);
    }
}
