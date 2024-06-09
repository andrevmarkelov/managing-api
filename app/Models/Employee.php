<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\EmployeeTrait;

class Employee extends Model
{
    use HasFactory, EmployeeTrait;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'salary'
    ];

    protected $hidden = ['updated_at', 'created_at'];

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class);
    }
}
