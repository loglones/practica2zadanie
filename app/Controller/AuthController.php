<?php
namespace App\Controller;

use Src\Request;
use Src\View;
use App\Model\User;
use Src\Auth\Auth;

class AuthController
{
    public function login(Request $request): string
    {
        if ($request->method === 'POST') {
            if (Auth::attempt($request->all())) {
                error_log("Login attempt: " . print_r($request->all(), true));
                $user = Auth::user();
                if ($user->role === 'admin') {
                    app()->route->redirect('/admin');
                } else {
                    app()->route->redirect('/employee');
                }
            } else {
                return new View('site.login', ['message' => 'Неправильные логин или пароль']);
            }
        }
        return new View('site.login');
    }

    public function logout(): void
    {
        Auth::logout();
        app()->route->redirect('/');
    }
}
