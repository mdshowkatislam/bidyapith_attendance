<?php

namespace App\Models;

use App\Traits\CreatedUId;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use CreatedUId;

    protected $table = 'branches';

    protected $fillable = [
        'uid',
        'branch_id',
        'branch_name',
        'branch_name_en',
        'branch_location',
        'head_of_branch_id',
        'eiin',
        'rec_status',
        'created_at',
        'updated_at',
    ];

    //  public function branchHead()
    // {
    //     return $this->belongsTo(Teacher::class, 'head_of_branch_id', 'uid')->select('uid', 'pdsid', 'caid', 'name_en', 'index_number');
    // }
}
