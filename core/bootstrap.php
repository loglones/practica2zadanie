<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Путь до директории с конфигурационными файлами
const DIR_CONFIG = '/../config';
require __DIR__ . '/../vendor/autoload.php';

function getConfigs(string $path = DIR_CONFIG): array
{
    $settings = [];
    foreach (scandir(__DIR__ . $path) as $file) {
        $name = explode('.', $file)[0];
        if (!empty($name)) {
            $settings[$name] = include __DIR__ . "$path/$file";
        }
    }
    return $settings;
}

require_once __DIR__ . '/../routes/web.php';
return new Src\Application(new Src\Settings(getConfigs()));

function app()
{
    global $app;
    return $app;
}

return $app;