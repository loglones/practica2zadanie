<?php

namespace App\Controller;

use App\Model\Student;
use App\Model\Group;
use App\Model\Grade;
use App\Model\Discipline;
use Src\View;
use Src\Request;
use App\Model\User;


class Authorised
{
    public function makeStudent(Request $request): string
    {
        $groups = \App\Model\Group::all();
        if ($groups->isEmpty()) {
            throw new \RuntimeException('No groups found in database');
        }
        if ($request->method === 'POST') {
            $validator = new \App\Validators\ValidatorRus();
            $rules = [
                'surname' => ['required', 'russian', 'name'],
                'name' => ['required', 'russian', 'name'],
                'patronymic' => ['russian', 'name'],
                'gender' => ['required'],
                'address' => ['required', 'address'],
                'group_id' => ['required']
            ];
            $group = Group::where('name', $request->group_id)->first();
            if ($validator->validate($request->all(), $rules)) {
                // Все данные валидны, создаем студента
                Student::create([
                    'surname' => $request->surname,
                    'name' => $request->name,
                    'patronymic' => $request->patronymic,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'user_id' => app()->auth::user()->id,
                    'group_id' => $request->group_id
                ]);

                return app()->route->redirect('/hello');
            } else {
                // Есть ошибки валидации
                return (string)new View('employee.makeStudent', [
                    'groups' => $groups,
                    'errors' => $validator->errors(),
                    'old' => $request->all()
                ]);
            }
        }

        // Если метод GET, показываем форму
        return (string)new View('employee.makeStudent', ['groups' => $groups]);
    }

    public function makeGroup(Request $request): string
    {
        if ($request->method === 'POST') {
            $validator = new \App\Validators\ValidatorOnlyNum();

            if ($validator->validate($request->all())) {
                // Валидация пройдена
                Group::create([
                    'name' => $request->name
                ]);

                return app()->route->redirect('/hello');
            } else {
                // Есть ошибки валидации
                return (string)new View('employee.makeGroup', [
                    'error' => $validator->getFirstError(),
                    'oldName' => $request->name
                ]);
            }
        }

        // GET запрос
        return (string)new View('employee.makeGroup');
    }

    public function makeDiscipline(Request $request): string
    {
        if ($request->method === 'POST') {
            $validator = new \App\Validators\DisciplineValidator();

            if ($validator->validate($request->all())) {
                // Валидация успешна
                Discipline::create([
                    'name' => $request->makeGroup
                ]);

                return app()->route->redirect('/hello');
            } else {
                // Есть ошибки валидации
                return (string)new View('employee.makeDiscipline', [
                    'error' => $validator->getFirstError(),
                    'oldName' => $request->makeGroup
                ]);
            }
        }

        return (string)new View('employee.makeDiscipline');
    }

    public function showStudentGrades(Request $request): string
    {
        $disciplines = Discipline::all();
        $controlTypes = Discipline::select('control_type')
            ->distinct()
            ->pluck('control_type')
            ->toArray();

        if ($request->method === 'POST') {
            $disciplineName = $request->checkDisciplene;
            $hours = $request->hours;
            $controlType = $request->control_type;

            // Находим дисциплину
            $discipline = Discipline::where('name', $disciplineName)->first();

            if (!$discipline) {
                return (string)new View('employee.studentGrades', [
                    'disciplines' => $disciplines,
                    'controlTypes' => $controlTypes,
                    'message' => 'Дисциплина не найдена'
                ]);
            }

            // Получаем студентов с их оценками по этой дисциплине
            $results = [];
            foreach ($discipline->students as $student) {
                $grade = $student->grades()
                    ->wherePivot('discipline_id', $discipline->id)
                    ->first();

                // Фильтрация по часам и типу контроля
                if (($hours && $discipline->hours != $hours) ||
                    ($controlType && $discipline->control_type != $controlType)) {
                    continue;
                }

                $results[] = [
                    'student' => $student->surname . ' ' . $student->name,
                    'discipline' => $discipline->name,
                    'grade' => $grade ? $grade->number : 'нет оценки',
                    'hours' => $discipline->hours,
                    'control_type' => $discipline->control_type
                ];
            }

            return (string)new View('employee.studentGrades', [
                'disciplines' => $disciplines,
                'controlTypes' => $controlTypes,
                'results' => $results,
                'selectedDiscipline' => $disciplineName,
                'selectedHours' => $hours,
                'selectedControl' => $controlType
            ]);
        }

        return (string)new View('employee.studentGrades', [
            'disciplines' => $disciplines,
            'controlTypes' => $controlTypes
        ]);
    }

    public function showGroupGrades(Request $request): string
    {
        $groups = Group::all();
        $disciplines = Discipline::all();
        $results = [];

        if ($request->method === 'POST') {
            try {
                $disciplineName = $request->all()['checkDisciplene'] ?? null;
                $groupName = $request->all()['group_name'] ?? null;

                if (!$disciplineName || !$groupName) {
                    throw new \Exception("Не все параметры переданы");
                }

                $group = Group::where('name', $groupName)->first();
                $discipline = Discipline::where('name', $disciplineName)->first();

                if (!$group || !$discipline) {
                    throw new \Exception("Группа или дисциплина не найдены");
                }

                $students = $group->students()
                    ->with(['disciplines' => function($query) use ($discipline) {
                        $query->where('disciplines.id', $discipline->id);
                    }])
                    ->get();

                foreach ($students as $student) {
                    $grade = $student->disciplines->first()?->pivot->grade_id
                        ? Grade::find($student->disciplines->first()->pivot->grade_id)
                        : null;

                    $results[] = [
                        'student' => $student->surname . ' ' . $student->name,
                        'group' => $group->name,
                        'discipline' => $discipline->name,
                        'grade' => $grade ? $grade->number : 'нет оценки',
                        'hours' => $discipline->hours,
                        'control_type' => $discipline->control_type
                    ];
                }
            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
        }

        return (string)new View('employee.groupGrades', [
            'groups' => $groups,
            'disciplines' => $disciplines,
            'results' => $results,
            'message' => $message ?? null,
            'selectedDiscipline' => $request->all()['checkDisciplene'] ?? null,
            'selectedGroup' => $request->all()['group_name'] ?? null
        ]);
    }

    public function showGroupDisciplines(Request $request): string
    {
        $groups = Group::all();
        $disciplines = collect();

        if ($request->method === 'POST') {
            try {
                $groupId = $request->all()['group'] ?? null;

                if (!$groupId) {
                    throw new \Exception("Группа не выбрана");
                }

                $group = Group::find($groupId);

                if (!$group) {
                    throw new \Exception("Группа не найдена");
                }

                $disciplines = Discipline::whereHas('students', function($query) use ($groupId) {
                    $query->where('group_id', $groupId);
                })->distinct()->get();

            } catch (\Exception $e) {
                $message = $e->getMessage();
            }
        }

        return (string)new View('employee.groupDisciplines', [
            'groups' => $groups,
            'disciplines' => $disciplines,
            'selectedGroup' => $request->all()['group'] ?? null,
            'message' => $message ?? null
        ]);
    }

    public function register(Request $request): string
    {


        // Обработка POST-запроса
        if ($request->method === 'POST') {
            $validator = new \App\Validators\RegisterValidator();

            if ($validator->validate($request->all())) {
                // Валидация успешна
                User::create([
                    'name' => $request->name,
                    'login' => $request->login,
                    'password' => md5($request->password),
                    'role' => 'employee'
                ]);

                return app()->route->redirect('/hello');
            } else {
                // Есть ошибки валидации
                return (string)new View('admin.register', [
                    'error' => $validator->getFirstError(),
                    'old' => $request->all()
                ]);
            }
        }

        return (string)new View('admin.register');
    }
}