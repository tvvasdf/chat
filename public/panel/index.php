<?php

global $user;
if (!$user || $user->getAccess() < 3) {
    Main::showError(404);
    return;
}

$lobbies = Lobby::getUserLobbies($user, true);

if ($_POST) {
    Main::clearBuffer();
    echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
    exit;
}

Main::setTitle('Панель управления');
?>


<button class="btn btmspace-10 active" data-show-button="create-chat">Создать чат</button>
<button class="btn btmspace-10" data-show-button="edit-chats">Редактировать чаты</button>

<div class="wrapper row0 content btmspace-50">
    <div data-show-container="create-chat">
        <form method="post" data-type="form">
            <div class="form_block">
                <h2>Создание чата</h2>
                <input class="btmspace-10" type="text" id="name" placeholder="Название чата*" required="required" />
                <input class="btmspace-10" type="text" id="code" placeholder="Символьный код чата*" required="required" />
                <label>Публичный: <input class="checkbox" type="checkbox" id="public" checked="checked" value="true" /></label>
                <input class="btmspace-10" type="text" id="icon" placeholder="Картинка чата (будет реализовано позже)" />
            </div>
            <div class="form_block">
                <h2>Приглашенные пользователи</h2>
                <button type="button" data-append-button="invited" class="btn btmspace-30">Добавить</button>
                <div class="inputs_container" data-append-container="invited">
                    <input data-append-element="invited" class="btmspace-10" type="text" id="invited1" placeholder="Логин/ID пользователя" />
                </div>
            </div>
            <div class="form_block inputs_container" data-append-container="admins">
                <h2>Администраторы чата</h2>
                <button type="button" data-append-button="admins" class="btn btmspace-30">Добавить</button>
                <div class="inputs_container">
                    <input data-append-element="admins" class="btmspace-10" type="text" id="admins1" placeholder="Логин/ID пользователя" />
                </div>
            </div>
            <button type="submit" class="btn btmspace-30">Создать новый чат</button>
        </form>
    </div>

    <div data-show-container="edit-chats" hidden="hidden">
        <div class="one_third first">
            <?php if (!$lobbies): ?>
                <p>У вас еще нет чатов, которые вы можете администрировать</p>
            <?php endif; ?>
            <?php foreach ($lobbies as $lobby): ?>
                <?php if (isset($_GET['lobby_id']) && $lobby['id'] == $_GET['lobby_id']): ?>
                    <button class="btn btmspace-10 active"><?= $lobby['name'] ?></button>
                <?php else: ?>
                    <button class="btn btmspace-10" data-lobby-id="<?= $lobby['id'] ?>"><?= $lobby['name'] ?></button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="two_third">
            <div class="content">


            </div>
        </div>
    </div>
</div>



