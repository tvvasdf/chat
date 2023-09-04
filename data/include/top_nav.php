<?php

return [
    [
        'name' => 'Главная',
        'url' => '/',
    ],
    [
        'name' => 'Чаты',
        'url' => '/chat/',
        'auth' => true,
    ],
    [
        'name' => 'Выход',
        'url' => '?logout=Y',
        'auth' => true,
    ],
    [
        'name' => 'Панель управления',
        'url' => '/panel/',
        'auth' => true,
        'access' => 3
    ],
    [
        'name' => 'Авторизация',
        'url' => '/auth/',
        'auth' => false,
    ],
];