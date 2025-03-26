<?php
use Src\Route;

Route::add('go',[App\Controller\Site::class,'index']);
Route::add('hello',[App\Controller\Site::class, 'hello']);