<?php

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$settings = require_once 'settings.php';

$db = new Medoo\Medoo($settings['db_data']);

session_start();

Main::init($settings);
User::$db = $db;
if ($user = User::authorized()) {
    Lobby::$db = $db;
    Messages::$db = $db;
}

if (isset($_GET['logout']) && $_GET['logout'] == 'Y' && $user) {
    User::logout();
    Main::redirect('/');
}
