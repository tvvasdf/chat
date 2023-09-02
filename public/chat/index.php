<?php

if (!User::authorized()) {
    Main::redirect('/auth/');
}
Main::setTitle('Чаты');
