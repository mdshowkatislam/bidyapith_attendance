<?php

namespace App\Models;
use App\Models\Department;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{

    protected $table='divisions';
    protected $fillable=['name'];
    public function department(){
        return $this->hasMany(Department::class);
    }
}
