<?php
namespace App\Controller;

use App\Model\Post;
use Src\View;
class Site
{
    public function index() :string
    {
        $posts = Post::all();
        return (new View())->render('site/post', ['posts' => $posts]);
    }
    public function hello() :string
    {
        $view = new View();
        return $view->render('site/hello', ['message' => 'hello working']);
    }
}