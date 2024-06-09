<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DepartmentTrait
{
    public function scopeWithAggregates($query)
    {
        return $query->select('departments.id', 'departments.name',
            DB::raw('COUNT(department_employee.employee_id) as employee_count'),
            DB::raw('MAX(employees.salary) as max_salary'))
            ->leftJoin('department_employee', 'departments.id', '=', 'department_employee.department_id')
            ->leftJoin('employees', 'employees.id', '=', 'department_employee.employee_id')
            ->groupBy('departments.id');
    }
}
