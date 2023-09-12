<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Medoo\Medoo;

class Chat implements MessageComponentInterface {

    /** @var SplObjectStorage Объект вида LobbyId => [ResourceId => User] */
    private SplObjectStorage $connections;
    private SplObjectStorage $clients;

    /** @var array Массив вида LobbyId => [Messages] */
    private array $messages = [];

    public static Medoo $db;

    public function __construct()
    {
        $messages = Messages::getAllMessages();
        foreach ($messages as $msg) {
            $this->messages[$msg['lobby_id']][] = $msg;
        }
        if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
            $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', explode('\\site', __DIR__)[0]);
        } else {
            $_SERVER['DOCUMENT_ROOT'] = explode('site/', __DIR__)[0];
        }
    }

    public function onOpen(ConnectionInterface $conn)
    {
        //check user and then
        //foreach user lobbies as lobby => attach
//        $this->connections->attach($conn->lobbyId, $conn->resourceId);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        global $settings;
        $message = json_decode($msg);
        if ($message->session_id && isset($settings['session_path'])) {
            $path = Main::getRoot($settings['session_path'] . 'sess_' . $message->session_id);
            $session = file_get_contents($path);
            echo '<pre>';
            echo '<pre>';
            var_dump($path);
            echo '</pre>';
            var_dump(is_file($path));
            echo '</pre>'; exit;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        throw new $e;
    }
}