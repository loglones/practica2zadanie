<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public $timestamps = false;
    protected $table = 'grades';
    protected $fillable = ['number'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_discipline', 'grade_id', 'student_id')
            ->withPivot('discipline_id');
    }

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'student_discipline', 'grade_id', 'discipline_id')
            ->withPivot('student_id');
    }

}