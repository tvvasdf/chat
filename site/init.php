<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php')) {
    require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
}

$settings = require_once 'settings.php';

date_default_timezone_set($settings['timezone']);

$db = new Medoo\Medoo($settings['db_data']);

session_start();

Main::init($settings);
User::$db = $db;
Lobby::$db = $db;
Messages::$db = $db;

if (isset($_GET['logout']) && $_GET['logout'] == 'Y' && $user = User::authorized()) {
    User::logout();
    Main::redirect('/');
}

//todo рефакторинг всех классов
