<?php

use SITE\Lobby;
use SITE\Main;
use SITE\Messages;
use SITE\User;

$settings = require_once 'settings.php';

if ($settings['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

Main::init($settings);
User::init($settings['db_data']);
if (User::authorized()) {
    Lobby::init($settings['db_data']);
    Messages::init($settings['db_data']);
}
