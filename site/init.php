<?php

$settings = require_once 'settings.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
spl_autoload_register(function ($class) {
    require_once 'class/' . $class . '.php';
});

Main::init($settings);
User::init($settings['db_data']);
if (User::authorized()) {
    Lobby::init($settings['db_data']);
    Messages::init($settings['db_data']);
}
