<?php

use SITE\Main;
use SITE\User;

if (User::authorized()) {
    Main::redirect('/chat/');
}
Main::setTitle('Авторизация');
?>

<div class="wrapper row0 content btmspace-50" style="text-align: center">
    <div class="three_quarter first" style="float: none">
        <div class="flex space_evenly form_block">
            <button value="Авторизоваться">Авторизоваться</button>
            <button value="Зарегистрироваться">Зарегистрироваться</button>
        </div>
        <div class="form_block">
            <form method="post" data-type="form" data-form="login">
                <label class="flex left">
                    <h1>Авторизация</h1>
                </label>
                <label class="flex center">
                    <input id="login" class="btmspace-15" required="" type="text" value="" placeholder="Логин*" />
                </label>
                <label class="flex center">
                    <input id="password" class="btmspace-15" required="" type="password" value="" placeholder="Пароль*" />
                </label>
                <label class="flex center">
                    <button type="submit" value="Авторизоваться">Авторизоваться</button>
                </label>
            </form>
        </div>

        <div class="form_block">
            <form method="post" data-type="form" data-form="register">
                <label class="flex left">
                    <h1>Регистрация</h1>
                </label>
                <label class="flex center">
                    <input class="btmspace-15" required="" type="text" value="" id="name" placeholder="Имя*" />
                </label>
                <label class="flex center">
                    <input class="btmspace-15" required="" type="text" value="" id="login" placeholder="Логин*" />
                </label>
                <label class="flex center">
                    <input class="btmspace-15" required="" type="password" id="password" value="" placeholder="Пароль*" />
                </label>
                <label class="flex center">
                    <input class="btmspace-15" required="" type="password" id="password_confirm" value="" placeholder="Повторите пароль*" />
                </label>
                <label class="flex center">
                    <button type="submit" value="Зарегистрироваться">Зарегистрироваться</button>
                </label>
            </form>
        </div>
    </div>
</div>





