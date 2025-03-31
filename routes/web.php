<?php
use Src\Route;
use App\Controller\{
    AuthController,
    AdminController,
    EmployeeController,
    MainController
};

$route = app()->route;

// 1. Публичные маршруты (не требуют аутентификации)
$route->add(['GET', 'POST'], '/login', [AuthController::class, 'login']);
$route->add('GET', '/logout', [AuthController::class, 'logout']);

// 2. Главный маршрут (отдельный контроллер)
$route->add('GET', '/', [MainController::class, 'index']);

// 3. Админские маршруты (группа с префиксом)
$route->group('/admin', function() use ($route) {
    // Основной маршрут админки
    $route->add('GET', '/home', [AdminController::class, 'index'])->middleware('auth:admin');

    // Создание сотрудника
    $route->add(['GET', 'POST'], '/employee/create', [AdminController::class, 'createEmployee'])
        ->middleware('auth:admin');
});

// 4. Маршруты сотрудника (группа с префиксом)
$route->group('/employee', function() use ($route) {
    // Основной маршрут сотрудника
    $route->add('GET', '/dashboard', [EmployeeController::class, 'index'])->middleware('auth:employee');

    // Создание студента
    $route->add(['GET', 'POST'], '/makeStudents', [EmployeeController::class, 'createStudent'])
        ->middleware('auth:employee');
});


//use Src\Route;
//
//Route::add('GET', '/test', function() {
//    return 'Test page works!';
//});
//
//Route::add(['GET', 'POST'], '/login', [AuthController::class, 'login']);