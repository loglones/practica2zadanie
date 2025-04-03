<?php
namespace App\Middlewares;

use Src\Request;

class TrimMiddleware
{
    public function handle(Request $request): void
    {
        // Тримим все строковые параметры запроса
        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                $request->set($key, trim($value));
            }
        }
    }
}