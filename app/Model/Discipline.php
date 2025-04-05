<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    public $timestamps = false;
    protected $table = 'disciplines';
    protected $fillable = ['name'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_discipline');
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_discipline')
            ->withPivot('grade_id');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'student_discipline', 'discipline_id', 'grade_id')
            ->withPivot('student_id');
    }
}