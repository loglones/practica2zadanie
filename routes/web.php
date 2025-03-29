<?php
use Src\Route;
use Src\View;
use App\Controller\{AuthController, AdminController, EmployeeController};

$route = app()->route;

// 1. Аутентификация (публичные маршруты)
$route->add(['GET', 'POST'], '/login', [AuthController::class, 'login']);
$route->add('GET', '/logout', [AuthController::class, 'logout']);

// 2. Главный маршрут (публичный)
$route->add('GET', '/', [
    function() {
        if (app()->auth::check()) {
            $user = app()->auth::user();
            app()->route->redirect(
                $user->role === 'admin' ? '/admin-panel' : '/employee-area'
            );
            return null;
        }
        return new View('site.welcome');
    }
]);

// 3. Админская зона (защищённые маршруты)
$route->group('/admin-panel', function() use ($route) {
    $route->add('GET', '', [AdminController::class, 'dashboard'])->middleware('auth:admin');
    $route->add(['GET', 'POST'], '/add-employee', [AdminController::class, 'createEmployee'])->middleware('auth:admin');
});

// 4. Зона сотрудника (защищённые маршруты)
$route->group('/employee-area', function() use ($route) {
    $route->add('GET', '', [EmployeeController::class, 'dashboard'])->middleware('auth:employee');
    $route->add(['GET', 'POST'], '/add-student', [EmployeeController::class, 'createStudent'])->middleware('auth:employee');
});
//use Src\Route;
//
//Route::add('GET', '/test', function() {
//    return 'Test page works!';
//});
//
//Route::add(['GET', 'POST'], '/login', [AuthController::class, 'login']);