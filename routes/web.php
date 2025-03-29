<?php
use Src\Route;
use App\Controller\{SiteController, AuthController, AdminController, EmployeeController};

// Аутентификация
Route::add(['GET', 'POST'], '/login', [AuthController::class, 'login']);
Route::add('GET', '/logout', [AuthController::class, 'logout']);

// Админка
Route::group('/admin', function () {
    Route::add('GET', '', [AdminController::class, 'index'])->middleware('auth:admin');
    Route::add(['GET', 'POST'], '/register', [AdminController::class, 'createEmployee'])->middleware('auth:admin');
});

// Сотрудник деканата
Route::group('/employee', function () {
    Route::add('GET', '', [EmployeeController::class, 'index'])->middleware('auth:employee');
    Route::add(['GET', 'POST'], '/students/create', [EmployeeController::class, 'createStudent'])->middleware('auth:employee');
    // Другие маршруты сотрудника
});

// Главная
Route::add('GET', '/', function () {
    if (app()->auth::check()) {
        $user = app()->auth::user();
        app()->route->redirect($user->role === 'admin' ? '/admin' : '/employee');
    }
    return (new View())->render('site.welcome');
});