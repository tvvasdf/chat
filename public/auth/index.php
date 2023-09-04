<?php

if (User::authorized() && !$_POST) {
    Main::redirect('/chat/');
}

if (isset($_POST['login']) && isset($_POST['password']) && isset($_POST['form_name'])) {
    switch ($_POST['form_name']) {
        case 'login':
            if (User::login($_POST['login'], $_POST['password'])) {
                Main::sendJson([
                    'success' => true,
                    'redirect' => '/',
                ]);
            }
            break;

        case 'register':
            if ($_POST['password'] != $_POST['password_confirm']) {
                Main::sendJson([
                    'success' => false,
                    'message' => 'Пароли не совпадают'
                ]);
            }
            $data = [
                'login' => $_POST['login'],
                'password' => $_POST['password'],
                'name' => isset($_POST['name']) ? : '',
            ];
            if (User::register($data)) {
                Main::sendJson([
                    'success' => true,
                    'redirect' => '/',
                ]);
            }
            break;
    }
    Main::sendJson([
        'success' => false,
        'message' => User::getLastError() ? : 'Произошла ошибка'
    ]);
}

Main::setTitle('Авторизация');
?>

<div class="wrapper row0 content btmspace-50" style="text-align: center">
    <div class="three_quarter first" style="float: none">
        <div class="flex space_evenly auth_change form_block">
            <button class="active auth_change" data-show-button="auth" value="Авторизоваться">Авторизоваться</button>
            <button class="auth_change" data-show-button="register" value="Зарегистрироваться">Зарегистрироваться</button>
        </div>
        <div class="form_block" data-show-container="auth">
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

        <div class="form_block" data-show-container="register" hidden="hidden">
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





