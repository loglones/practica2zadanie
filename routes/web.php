<?php
use Src\Route;

Route::add('GET', '/hello', [App\Controller\Site::class, 'hello'])
    ->middleware('auth');
Route::add(['GET', 'POST'], '/signup', [App\Controller\Site::class, 'signup']);
Route::add(['GET', 'POST'], '/login', [App\Controller\Site::class, 'login']);
Route::add('GET', '/logout', [App\Controller\Site::class, 'logout']);
