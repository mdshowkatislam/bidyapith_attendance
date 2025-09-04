<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait CreatedUId
{
    public static function bootCreatedUId()
    {
        // uid when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->uid = hexdec(uniqid());
            }
        });
    }
}