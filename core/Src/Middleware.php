<?php
namespace Src;

use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\MarkBased;
use FastRoute\Dispatcher\MarkBased as Dispatcher;
use Src\Traits\SingletonTrait;

class Middleware
{
    //Используем трейт
    use SingletonTrait;

    private RouteCollector $middlewareCollector;
    private array $globalMiddlewares = [];

    public function addGlobal(string $middleware): void
    {
        $this->globalMiddlewares[] = $middleware;
    }
    public function add($httpMethod, string $route, array $action): void
    {
        $this->middlewareCollector->addRoute($httpMethod, $route, $action);
    }

    public function group(string $prefix, callable $callback): void
    {
        $this->middlewareCollector->addGroup($prefix, $callback);
    }

    //Конструктор скрыт. Вызывается только один раз
    private function __construct()
    {
        $this->middlewareCollector = new RouteCollector(new Std(), new MarkBased());
    }

    //Запуск всех middlewares для текущего маршрута
    public function runMiddlewares(string $httpMethod, string $uri): Request
    {
        $request = new Request();

        // Сначала выполняем глобальные middleware
        foreach ($this->globalMiddlewares as $middleware) {
            (new $middleware)->handle($request);
        }

        // Затем выполняем route-specific middleware
        foreach ($this->getMiddlewaresForRoute($httpMethod, $uri) as $middleware) {
            $args = explode(':', $middleware);
            $routeMiddleware = app()->settings->app['routeMiddleware'][$args[0]] ?? null;
            if ($routeMiddleware) {
                (new $routeMiddleware)->handle($request, $args[1] ?? null);
            }
        }

        return $request;
    }

    //Поиск middlewares по адресу
    private function getMiddlewaresForRoute(string $httpMethod, string $uri): array
    {
        $dispatcherMiddleware = new Dispatcher($this->middlewareCollector->getData());
        return $dispatcherMiddleware->dispatch($httpMethod, $uri)[1] ?? [];
    }
}
