<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
        protected $table = 'attendance_statuses';
        protected $fillable = ['name', 'short_form', 'status'];

        protected $casts = [
                'status' => 'boolean',
        ];
}
