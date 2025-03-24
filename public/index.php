<?php
declare(strict_types=1);//Включаем запрет на неявное преобразование типов

try {
    //Создаем экземпляр приложения и запускаем е
    $app = require_once __DIR__ . '/../core/bootstrap.php';
    $app->run();
} catch (\Throwable $exception) {

    echo '<pre>';
    print_r($exception);
    echo '</pre>';
}