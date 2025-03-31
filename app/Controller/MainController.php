<?php
namespace App\Controller;

use Src\View;
use Src\Auth\Auth;

class MainController
{
    public function index(): string
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                header('Location: /admin/home');
            } else {
                header('Location: /employee/dashboard');
            }
            exit;
        }
        return (new View())->render('site.welcome');
    }
}