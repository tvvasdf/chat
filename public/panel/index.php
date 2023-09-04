<?php

global $user;
if (!$user || $user->getAccess() < 3) {
    Main::showError(404);
    return;
}

$lobbies = Lobby::getUserLobbies($user, true);
$selected = isset($_GET['lobby_id']) ? $lobbies[array_search($_GET['lobby_id'], array_column($lobbies, 'id'))] : false;

if ($_POST) {
    Main::includeTemplateFile('/include/forms/panel.php');
}

Main::setTitle('Панель управления');
?>


<button class="btn btmspace-10 active" data-show-button="create-chat">Создать чат</button>
<button class="btn btmspace-10" data-show-button="edit-chats">Редактировать чаты</button>

<div class="wrapper row0 content btmspace-50">
    <div data-show-container="create-chat">
        <form method="post" data-type="form" data-form="create-chat">
            <div class="form_block">
                <h2>Создание чата</h2>
                <label><input class="btmspace-10" type="text" id="name" placeholder="Название чата*" required="required" /></label>
                <label><input class="btmspace-10" type="text" id="code" placeholder="Символьный код чата*" required="required" /></label>
                <label>Публичный: <input class="checkbox" type="checkbox" id="public" checked="checked" value="true" /></label>
                <label><input class="btmspace-10" type="text" id="icon" placeholder="Картинка чата (будет реализовано позже)" /></label>
            </div>
            <div class="form_block">
                <h2>Приглашенные пользователи</h2>
                <button type="button" data-append-button="invited" class="btn btmspace-30">Добавить</button>
                <div class="inputs_container" data-append-container="invited">
                    <input data-append-element="invited" class="btmspace-10" type="text" id="invited1" placeholder="Логин/ID пользователя" />
                </div>
            </div>
            <div class="form_block">
                <h2>Администраторы чата</h2>
                <button type="button" data-append-button="admins" class="btn btmspace-30">Добавить</button>
                <div class="inputs_container" data-append-container="admins">
                    <input data-append-element="admins" class="btmspace-10" type="text" id="admins1" placeholder="Логин/ID пользователя" />
                </div>
            </div>
            <button type="submit" data-type="" class="btn btmspace-30">Создать новый чат</button>
        </form>
    </div>

    <div data-show-container="edit-chats" hidden="hidden">
        <div class="one_third first">
            <?php if (!$lobbies): ?>
                <p>У вас еще нет чатов, которые вы можете администрировать</p>
            <?php endif; ?>
            <?php foreach ($lobbies as $lobby): ?>
                <?php if (isset($_GET['lobby_id']) && $lobby['id'] == $_GET['lobby_id']): ?>
                    <button class="btn btmspace-10 width100 active"><?= $lobby['name'] ?></button>
                <?php else: ?>
                    <button class="btn btmspace-10 width100" data-lobby-id="<?= $lobby['id'] ?>"><?= $lobby['name'] ?></button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="two_third">
            <div class="content" data-replace="chat-edit-form">
                <?php if ($lobby = $selected): ?>
                    <div class="form_block">
                        <h2>Редактирование чата</h2>
                        <label><input class="btmspace-10" type="text" id="name" placeholder="Название чата" required="required" value="<?= $lobby['name'] ?>" /></label>
                        <label><input class="btmspace-10" type="text" placeholder="Символьный код чата" disabled="disabled" value="<?= $lobby['code'] ?>" /></label>
                        <label>Публичный: <input class="checkbox" type="checkbox" id="public" checked="checked" value="true" /></label>
                        <label><input class="btmspace-10" type="text" id="icon" placeholder="Картинка чата (будет реализовано позже)" /></label>
                    </div>
                    <div class="form_block">
                        <h2>Приглашенные пользователи</h2>
                        <button type="button" data-append-button="invited-edit" class="btn btmspace-30">Добавить</button>
                        <div class="inputs_container" data-append-container="invited-edit">
                            <?php if (!$invited = unserialize($lobby['serialized_invited_id'])): ?>
                                <input data-append-element="invited-edit" class="btmspace-10" type="text" id="invited-edit1" placeholder="Логин/ID пользователя" />
                            <?php else: ?>
                                <?php foreach ($invited as $key => $id): ?>
                                    <input data-append-element="invited-edit" class="btmspace-10" type="text" id="invited-edit<?= $key + 1?>" value="<?= $id ?>" placeholder="Логин/ID пользователя" />
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form_block">
                        <h2>Администраторы чата</h2>
                        <button type="button" data-append-button="admins-edit" class="btn btmspace-30">Добавить</button>
                        <div class="inputs_container" data-append-container="admins-edit">
                            <?php if (!$admins = unserialize($lobby['serialized_admins_id'])): ?>
                                <input data-append-element="admins-edit" class="btmspace-10" type="text" id="admins-edit1" placeholder="Логин/ID пользователя" />
                            <?php else: ?>
                                <?php foreach ($admins as $key => $id): ?>
                                    <input data-append-element="admins-edit" class="btmspace-10" type="text" id="admins-edit<?= $key + 1?>" value="<?= $id ?>" placeholder="Логин/ID пользователя" />
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form_block">
                        <h2>Забаненные пользователи</h2>
                        <button type="button" data-append-button="banned-edit" class="btn btmspace-30">Добавить</button>
                        <div class="inputs_container" data-append-container="banned-edit">
                            <?php if (!$banned = unserialize($lobby['serialized_banned_id'])): ?>
                                <input data-append-element="banned-edit" class="btmspace-10" type="text" id="banned-edit1" placeholder="Логин/ID пользователя" />
                            <?php else: ?>
                                <?php foreach ($banned as $key => $id): ?>
                                    <input data-append-element="banned-edit" class="btmspace-10" type="text" id="banned-edit<?= $key + 1?>" value="<?= $id ?>" placeholder="Логин/ID пользователя" />
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="submit" data-type="" class="btn btmspace-30">Применить изменения</button>
                <?php else: ?>
                    <p>Выберите чат</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



