<?php
namespace App\Controller;

use App\Model\Post;
use Src\Request;
use Src\View;
class Site
{
    public function index(Request $request) :string
    {
        $posts = Post::where('id', $request->id)->get();
        return (new View())->render('site/post', ['posts' => $posts]);

    }
    public function hello() :string
    {
        $view = new View();
        return $view->render('site/hello', ['message' => 'hello working']);
    }
}