<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;
    protected $table = 'students';
    protected $fillable = ['surname', 'name', 'patronymic', 'gender', 'birth_date', 'address', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'student_discipline')
            ->withPivot('grade_id');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'student_discipline', 'student_id', 'grade_id')
            ->withPivot('discipline_id');
    }
}