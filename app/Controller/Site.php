<?php
namespace App\Controller;

use Src\View;
class Site
{
    public function index()
    {
        $view = new View();
        return $view->render('site/hello', ['message' => 'index working']);

    }
    public function hello()
    {
        $view = new View();
        return $view->render('site/hello', ['message' => 'hello working']);
    }
}