<?php

use SITE\Main;
use SITE\User;

if (!User::authorized()) {
    Main::redirect('/auth/');
}
Main::setTitle('Чаты');
?>

