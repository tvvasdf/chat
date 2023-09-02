<?php

$settings = require_once 'settings.php';

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

Main::init($settings);
User::init($settings['db_data']);
if (User::authorized()) {
    Lobby::init($settings['db_data']);
    Messages::init($settings['db_data']);
}
