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
        <?php foreach ($lobbies as $lobby): ?>
            <?php if (isset($_GET['lobby_id']) && $lobby['id'] == $_GET['lobby_id']): ?>
                <button class="btn btmspace-10 active width100"><?= $lobby['name'] ?></button>
            <?php else: ?>
                <button class="btn btmspace-10 width100" data-lobby-id="<?= $lobby['id'] ?>"><?= $lobby['name'] ?></button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="two_third">
        <div class="content" data-replace="messages">
            <?php if (isset($messages)): ?>
                <?php
                echo '<pre>';
                var_dump($messages);
                echo '</pre>';
                ?>
            <?php else: ?>
                Выберите чат
            <?php endif; ?>
        </div>
    </div>
</div>
