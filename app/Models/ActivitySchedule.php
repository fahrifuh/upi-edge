<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivitySchedule extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class, 'activity_schedules_lecturers');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'activity_schedules_students');
    }
}
