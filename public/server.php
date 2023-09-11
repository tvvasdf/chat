<?php

if (!$argc) {
    echo 'Запускать только с командной строки';
}

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../site/init.php';

use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;


try {
    $ws = new WsServer(new Chat);
    $server = IoServer::factory(new HttpServer($ws), 8080);
    $server->run();
} catch(Exception $e) {
    $console = '*************************' . PHP_EOL;
    $console .= 'При выполнении скрипта произошла ошибка: ' . PHP_EOL;
    $console .= $e->getMessage() . PHP_EOL . PHP_EOL;
    foreach ($e->getTrace() as $key => $exception) {
        $console .= '#' . $key . ' Файл: ' . $exception['file'] . PHP_EOL;
        $console .= 'Строка: ' . $exception['line'] . PHP_EOL . PHP_EOL;
    }
    $console .= '*************************' . PHP_EOL;
    echo $console;
    if (isset($server)) {
        $server->loop->stop();
    }
}

