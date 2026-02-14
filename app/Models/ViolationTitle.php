<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViolationTitle extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function studentViolations()
    {
        return $this->hasMany(StudentViolation::class, 'case_id');
    }
}
