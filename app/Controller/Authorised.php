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
            $group = Group::where('name', $request->group_id)->first();
            Student::create([
                'surname' => $request->surname,
                'name' => $request->name,
                'patronymic' => $request->patronymic,
                'gender' => $request->gender,
                'dateBirthday' => $request->dateBirthday,
                'address' => $request->address,
                'group_id' => $group->id // Используем реальный ID группы
            ]);
            return app()->route->redirect('/hello');



        }

        // Если метод GET, показываем форму
        return (string) new View('employee.makeStudent', ['groups' => $groups]);
    }
    public function makeGroup(Request $request): string
    {
        if ($request->method === 'POST') {
            Group::create([
                'name' => $request->group_number
            ]);
            return app()->route->redirect('/hello');
        }

        // Если метод GET, показываем форму
        return (string) new View('employee.makeGroup');
    }
    public function makeDiscipline(Request $request): string
    {
        if ($request->method === 'POST') {
            Discipline::create([
                'name' => $request->makeGroup
            ]);
            return app()->route->redirect('/hello');
        }

        // GET запрос - показываем форму
        return (string) new View('employee.makeDiscipline');
    }
    public function showStudentGrades(Request $request): string
    {
        // Получаем все дисциплины
        $disciplines = Discipline::all();

        // Получаем уникальные типы контроля из таблицы grades
        $controlTypes = Grade::select('control_type')
            ->whereNotNull('control_type')
            ->distinct()
            ->pluck('control_type')
            ->toArray();

        // Обработка формы
        if ($request->method === 'POST') {
            $disciplineName = $request->checkDisciplene;
            $hours = $request->hours;
            $controlType = $request->control_type; // Получаем тип контроля из формы

            // Получаем оценки по выбранным параметрам
            $grades = Grade::with(['student', 'discipline'])
                ->whereHas('discipline', function($query) use ($disciplineName) {
                    $query->where('name', $disciplineName);
                })
                ->when($hours, function($query, $hours) {
                    return $query->where('hours', $hours);
                })
                ->when($controlType, function($query, $controlType) {
                    return $query->where('control_type', $controlType);
                })
                ->get();

            // Формируем результаты для вывода
            $results = [];
            foreach ($grades as $grade) {
                $results[] = [
                    'student' => $grade->student->surname . ' ' . $grade->student->name,
                    'discipline' => $grade->discipline->name,
                    'grade' => $grade->grade ?? 'нет оценки',
                    'hours' => $grade->hours,
                    'control_type' => $grade->control_type ?? 'не указан'
                ];
            }

            return (string) new View('employee.studentGrades', [
                'disciplines' => $disciplines,
                'controlTypes' => $controlTypes,
                'results' => $results,
                'selectedDiscipline' => $disciplineName,
                'selectedHours' => $hours,
                'selectedControl' => $controlType
            ]);
        }

        // GET запрос - показываем форму
        return (string) new View('employee.studentGrades', [
            'disciplines' => $disciplines,
            'controlTypes' => $controlTypes
        ]);
    }
    public function showGroupGrades(Request $request): string
    {
        // Получаем все группы и дисциплины для выпадающих списков
        $groups = Group::all();
        $disciplines = Discipline::all();

        if ($request->method === 'POST') {
            // Получаем выбранные значения из формы
            $disciplineName = $request->checkDisciplene;
            $groupName = $request->group_name; // Изменим name в форме на group_name

            // Ищем оценки для выбранной группы и дисциплины
            $grades = Grade::with(['student', 'discipline', 'student.group'])
                ->whereHas('discipline', function($query) use ($disciplineName) {
                    $query->where('name', $disciplineName);
                })
                ->whereHas('student.group', function($query) use ($groupName) {
                    $query->where('name', $groupName);
                })
                ->get();

            // Формируем результаты для отображения
            $results = [];
            foreach ($grades as $grade) {
                $results[] = [
                    'student' => $grade->student->surname . ' ' . $grade->student->name,
                    'group' => $grade->student->group->name,
                    'discipline' => $grade->discipline->name,
                    'grade' => $grade->grade ?? 'нет оценки',
                    'hours' => $grade->hours,
                    'control_type' => $grade->control_type
                ];
            }

            return (string) new View('employee.groupGrades', [
                'groups' => $groups,
                'disciplines' => $disciplines,
                'results' => $results,
                'selectedDiscipline' => $disciplineName,
                'selectedGroup' => $groupName
            ]);
        }

        // GET запрос - показываем форму
        return (string) new View('employee.groupGrades', [
            'groups' => $groups,
            'disciplines' => $disciplines
        ]);
    }
    public function showGroupDisciplines(Request $request): string
    {
        $groups = Group::all();

        if ($request->method === 'POST') {
            // 1. Находим конкретную группу по ID
            $group = Group::find($request->group);

            // 2. Проверяем, что группа найдена
            if (!$group) {
                throw new \Exception("Группа не найдена");
            }

            // 3. Получаем дисциплины через отношение
            $disciplines = $group->disciplines;

            return (string) new View('employee.groupDisciplines', [
                'groups' => $groups,
                'disciplines' => $disciplines,
                'selectedGroup' => $request->group
            ]);
        }

        return (string) new View('employee.groupDisciplines', [
            'groups' => $groups,
            'disciplines' => collect()
        ]);
    }
    public function register(Request $request): string
    {


        // Обработка POST-запроса
        if ($request->method === 'POST') {
            try {
                // Отладочный вывод (временный)
                error_log('Received data: ' . print_r($request->all(), true));

                // Проверка данных
                $name = trim($request->name ?? '');
                $login = trim($request->login ?? '');
                $password = trim($request->password ?? '');

                if ($name === '' || $login === '' || $password === '') {
                    throw new \Exception('Все поля обязательны для заполнения');
                }

                if (strlen($password) < 6) {
                    throw new \Exception('Пароль должен содержать минимум 6 символов');
                }

                if (User::where('login', $login)->exists()) {
                    throw new \Exception('Этот логин уже занят');
                }

                // Создание пользователя
                $user = new User([
                    'name' => $name,
                    'login' => $login,
                    'password' => md5($password),
                    'role' => 'employee'
                ]);

                if ($user->save()) {
                    $message = 'Сотрудник успешно зарегистрирован';
                } else {
                    throw new \Exception('Ошибка при сохранении пользователя');
                }
            } catch (\Exception $e) {
                $message = $e->getMessage();
                error_log('Registration error: ' . $e->getMessage());
            }
        }

        return (string) new View('admin.register', [
            'message' => $message ?? null
        ]);
    }
}