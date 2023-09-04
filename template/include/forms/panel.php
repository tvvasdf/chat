<?php

Main::clearBuffer();

function getFields($userId): array {
    if (!$_POST['name'] || !$_POST['code']) {
        return [];
    }

    $fields = [
        'admins' => [
            $userId
        ],
        'invited' => [],
    ];

    foreach ($fields as $name => $value) {
        $i = 1;
        while (isset($_POST[$name . $i]) && $userData = $_POST[$name . $i]) {
            $id = is_numeric($userData) ? $userData : (new User($userData))->getId();
            if ($id && User::exists($id)) {
                $fields[$name][] = $id;
            }
            $i++;
        }
    }

    $fields['name'] = $_POST['name'];
    $fields['code'] = $_POST['code'];
    $fields['public'] = $_POST['public'];
//    $fields['icon'] = $_POST['icon'];
    $fields['serialized_admins_id'] = serialize($fields['admins']);
    $fields['serialized_users_id'] = serialize([$userId]);
    $fields['serialized_invited_id'] = serialize($fields['invited']);
    unset($fields['admins']);
    unset($fields['invited']);

    return $fields;
}

global $user;
$result = false;
$fields = getFields($user->getId());

if (!$fields) {
    Main::sendJson([
        'success' => false,
        'message' => 'Ошибка',
    ]);
}

if ($_POST['form_name'] == 'create-chat') {
    $result = Lobby::create($fields);
}

//if ($_POST['form_name'] == 'edit-chat') {
//    $result = Lobby::create($fields);
//}

if ($result) {
    Main::sendJson([
        'success' => true,
        'message' => 'Чат создан'
    ]);
} else {
    Main::sendJson([
        'success' => false,
        'message' => Lobby::getLastError() ? : 'Ошибка',
    ]);
}
