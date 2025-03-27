<?php
use Src\Route;

Route::add('go',[App\Controller\Site::class,'index']);
Route::add('hello',[App\Controller\Site::class, 'hello']);
Route::add('signup', [App\Controller\Site::class, 'signup']);
Route::add('login', [App\Controller\Site::class, 'login']);
Route::add('logout', [App\Controller\Site::class, 'logout']);
