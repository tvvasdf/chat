<?php

if (!$argc) {
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
}

$settings = require_once 'settings.php';

$db = new Medoo\Medoo($settings['db_data']);

Main::init($settings);
User::$db = $db;
Lobby::$db = $db;
Messages::$db = $db;
$user = User::authorized();

if (isset($_GET['logout']) && $_GET['logout'] == 'Y' && $user) {
    User::logout();
    Main::redirect('/');
}

//todo рефакторинг всех классов
