<?php
namespace App\Controller;

use Src\Request;
use Src\View;
use App\Model\User;

class AdminController
{
    public function index(): string
    {
        return (new View())->render('admin.dashboard');
    }

    public function createEmployee(Request $request): string
    {
        if ($request->method === 'POST') {
            try {
                $data = $request->all();
                $data['role'] = 'employee';
                User::create($data);
                app()->route->redirect('/admin');
            } catch (\Exception $e) {
                return new View('admin.create-employee', ['message' => 'Ошибка регистрации']);
            }
        }
        return new View('admin.create-employee');
    }
}