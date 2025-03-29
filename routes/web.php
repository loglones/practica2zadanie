<?php
use Src\Route;

Route::add('GET', '/login', function() {
    return 'Test login page';
});

Route::add('GET', '/', function() {
    return 'Home page';
});