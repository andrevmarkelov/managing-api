<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\DepartmentTrait;

class Department extends Model
{
    use HasFactory, DepartmentTrait;

    protected $fillable = [
        'name'
    ];

    protected $hidden = ['updated_at', 'created_at'];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }
}
