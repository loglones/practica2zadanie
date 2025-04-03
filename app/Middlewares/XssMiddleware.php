<?php
namespace App\Middlewares;

use Src\Request;

class XssMiddleware
{
    public function handle(Request $request): void
    {
        // Очищаем все входные данные от XSS
        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                $request->set($key, $this->sanitize($value));
            } elseif (is_array($value)) {
                $request->set($key, $this->sanitizeArray($value));
            }
        }
    }

    private function sanitize(string $value): string
    {
        // Основные XSS-фильтры
        $value = strip_tags($value);
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = str_replace(['<', '>'], ['&lt;', '&gt;'], $value);

        // Дополнительная защита от JavaScript
        $value = preg_replace('/(javascript|jscript|js|vbscript|alert|onload|onerror|onclick|onmouseover):/i', '', $value);

        return $value;
    }

    private function sanitizeArray(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $result[$key] = $this->sanitize($value);
            } elseif (is_array($value)) {
                $result[$key] = $this->sanitizeArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}