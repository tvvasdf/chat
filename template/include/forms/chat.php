<?php

if (!$_POST['message'] || !trim($_POST['message'])) {
    exit;
}

global $user;

Messages::add($user, $_SESSION['current_lobby'], strip_tags($_POST['message']));