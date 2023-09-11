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
        echo '<pre>';
        var_dump($_COOKIE['PHPSESSID']);
        echo '</pre>';exit;

    }

    public function onOpen(ConnectionInterface $conn)
    {
        //check user and then
        //foreach user lobbies as lobby => attach
        $this->connections->attach($conn->lobbyId, $conn->resourceId);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo '<pre>';
        var_dump($from);
        var_dump($msg);
        echo '</pre>';
    }

    public function onClose(ConnectionInterface $conn)
    {
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        throw new $e;
    }
}