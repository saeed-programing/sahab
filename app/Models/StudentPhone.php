<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPhone extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getPhoneForLabelAttribute()
    {
        return [
            'Father' => 'پدر',
            'Mother' => 'مادر',
            'Student' => 'دانش‌آموز',
            'Other' => 'سایر'
        ][$this->phone_for] ?? 'نامشخص';
    }
}
