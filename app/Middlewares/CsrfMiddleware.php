<?php
namespace App\Middlewares;

use Src\Request;
use Src\Session;

class CsrfMiddleware
{
    public function handle(Request $request): void
    {

        if ($request->method === 'GET') {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
            return;
        }

        $token = $request->get('csrf_token');
        $sessionToken = Session::get('csrf_token');

        if (!$token || !$sessionToken || !hash_equals($sessionToken, $token)) {
            throw new \Exception('CSRF token mismatch');
        }
    }
}