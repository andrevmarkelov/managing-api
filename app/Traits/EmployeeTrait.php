<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait EmployeeTrait
{
    public function scopeWithBasicInfo($query)
    {
        return $query->select('id',
            DB::raw("CONCAT(last_name, ' ', first_name, ' ', middle_name) as full_name"),
            'gender',
            'salary');
    }
}
