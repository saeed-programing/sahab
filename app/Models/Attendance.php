<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'attendances';

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function register()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }


}
