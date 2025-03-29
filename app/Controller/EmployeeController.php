<?php
namespace App\Controller;

use Src\Request;
use Src\View;
use App\Model\{Student, Group, Discipline};

class EmployeeController
{
    public function index(): string
    {
        return (new View())->render('employee.dashboard');
    }

    public function createStudent(Request $request): string
    {
        if ($request->method === 'POST') {
            try {
                Student::create($request->all());
                app()->route->redirect('/employee/students');
            } catch (\Exception $e) {
                return new View('employee.create-student', [
                    'groups' => Group::all(),
                    'message' => 'Ошибка создания студента'
                ]);
            }
        }
        return new View('employee.create-student', ['groups' => Group::all()]);
    }

}