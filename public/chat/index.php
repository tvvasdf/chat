<?php

if (!User::authorized()) {
    Main::redirect('/auth/');
}

global $user;

$lobbies = Lobby::getUserLobbies($user);

Main::setTitle('Чаты');

if ($_POST) {
    Main::includeTemplateFile('/include/forms/chat.php');
}

if (isset($_GET['lobby_id'])) {
    $_SESSION['current_lobby'] = $_GET['lobby_id'];
}

if (isset($_SESSION['current_lobby'])) {
    $current = Lobby::getUserLobbies($user, false, ['id' => $_SESSION['current_lobby']])[0];
    $current['messages'] = Messages::getAllMessages($_SESSION['current_lobby']);
}
?>
<div class="group">
    <div class="one_third first">
        <h2 class="heading"><?php Main::showTitle(); ?></h2>
        <?php if (!$lobbies): ?>Вы еще не состоите ни в одном чате<?php endif; ?>
        <?php foreach ($lobbies as $lobby): ?>
            <?php if (isset($_GET['lobby_id']) && $lobby['id'] == $_GET['lobby_id']): ?>
                <button class="btn btmspace-10 active width100"><?= $lobby['name'] ?> [<?= $lobby['code'] ?>]</button>
            <?php else: ?>
                <button class="btn btmspace-10 width100" data-lobby-id="<?= $lobby['id'] ?>"><?= $lobby['name'] ?> [<?= $lobby['code'] ?>]</button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="two_third" data-replace="chat">
        <?php if (isset($current) && $current): ?>
            <h2 class="heading"><?= $current['name'] ?></h2>
            <div class="content messages_block">
            <div class="messages_list" data-replace="messages">
                <?php if (!$current['messages']): ?>В этом чате еще нету сообщений<?php endif; ?>
                <?php foreach ($current['messages'] as $message): ?>
                    <div class="messages_item">
                    <!--<img src="" alt="" class="user_icon" />-->
                    <div class="messages_info">
                        <div class="messages_user"><?= $message['author_name'] ?> [<?= $message['author_login'] ?>]</div>
                        <div class="messages_date"><?= $message['date'] ?></div>
                    </div>
                    <div class="messages_text"><?= $message['text'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="messages_form">
                <form method="post" name="send-message" data-type="form-messages" class="flex left end messages_form">
                    <textarea rows="1" required="required" type="text" id="message" placeholder="Напишите сообщение..."></textarea>
                    <button type="submit" data-type="submit" form="send-message">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
            <h2 class="heading">Выберите чат</h2>
        <?php endif; ?>
    </div>
</div>