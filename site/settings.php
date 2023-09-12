<?php

//todo рефакторинг
return [
    'debug' => true,
    'error_page' => 'template/error.php',
    'timezone' => 'Europe/Moscow',
    'session_path' => '/data/sessions/',
    'folder' => [
        'public' => 'public',
        'site' => 'site',
        'template' => 'template',
        'data' => 'data',
        'phpmyadmin' => 'pma',
    ],
    'db_data' => [
        'type' => 'mysql',
        'host' => 'localhost',
        'database' => 'socnet',
        'username' => 'root',
        'password' => 'Roma228!'
    ],
];
