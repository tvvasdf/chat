<?php

return [
    [
        'name' => 'Главная',
        'url' => '/',
    ],
    [
        'name' => 'Авторизация',
        'url' => '/auth/',
        'auth' => false,
    ],
    [
        'name' => 'Чаты',
        'url' => '/chat/',
        'auth' => true,
    ],
    [
        'name' => 'Панель управления',
        'url' => '/panel/',
        'auth' => true,
        'access' => 3
    ],
    [
        'name' => 'Выход',
        'url' => '?logout=Y',
        'auth' => true,
    ],
];