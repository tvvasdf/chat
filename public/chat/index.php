<?php

Main::setTitle('Чаты');
if (!User::authorized()) {
    Main::redirect('/auth/');
}

global $user;
$lobbies = Lobby::getUserLobbies($user);
if (isset($_GET['lobby_id'])) {
    $messages = Messages::getAllMessages($_GET['lobby_id']);
}

?>
<div class="group">
    <div class="one_third first">
        <h2 class="heading"><?php Main::showTitle(); ?></h2>
        <?php foreach ($lobbies as $lobby): ?>
            <?php if (isset($_GET['lobby_id']) && $lobby['id'] == $_GET['lobby_id']): ?>
                <button class="btn btmspace-10 active width100"><?= $lobby['name'] ?></button>
            <?php else: ?>
                <button class="btn btmspace-10 width100" data-lobby-id="<?= $lobby['id'] ?>"><?= $lobby['name'] ?></button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="two_third" data-replace="messages">
        <h2 class="heading">Название лобби</h2>
        <div class="content messages_block">
            <div class="messages_list">
                <div class="messages_item">
                    <!--<img src="" alt="" class="user_icon" />-->
                    <div class="messages_info">
                        <div class="messages_user">Пользователь [логин]</div>
                        <div class="messages_date">22:23</div>
                    </div>
                    <div class="messages_text">Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. Тестовое сообщение. </div>
                </div>
            </div>
            <div class="messages_form">
                <form method="post" name="send-message" data-type="form" class="flex left end messages_form" data-form="send-message">
                    <textarea rows="1" required="required" type="text" id="message" placeholder="Напишите сообщение..."></textarea>
                    <button type="submit" form="send-message">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php if (isset($messages)): ?>

<?php else: ?>
    Выберите чат
<?php endif; ?>