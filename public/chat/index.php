<?php

Main::setTitle('Чаты');
if (!User::authorized()) {
    Main::redirect('/auth/');
}

$user = User::getUser();
$lobbies = Lobby::getUserLobbies($user);
if (isset($_GET['lobby_id'])) {
    $messages = Messages::getAllMessages($_GET['lobby_id']);
}

?>
<h2 class="heading"><?php Main::showTitle(); ?></h2>

<div class="group" data-replace="messages">
    <div class="one_third first">
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
