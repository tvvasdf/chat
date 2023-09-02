<?php

use Medoo\Medoo;

class Messages
{
    private const TABLE_NAME = 'messages';
    private static Medoo $db;
    private static string $lastError = '';

    public static function init(array $data): void
    {
        self::$db = new Medoo($data);
    }

    public static function add($lobbyId, $text, $userId): bool
    {
        $result = self::$db->insert(
            self::TABLE_NAME,
            [
                'lobby_id' => $lobbyId,
                'text' => $text,
                'author_id' => $userId,
                'author_name' => User::getName($userId),
            ]
        );
        if ($result) {
            return true;
        } else {
            self::$lastError = self::$db->error;
            return false;
        }
    }

    public static function delete($lobbyId, $messageId): bool
    {
        $result = self::$db->delete(
            self::TABLE_NAME,
            [
                'lobby_id' => $lobbyId,
                'id' => $messageId
            ]
        );
        if ($result) {
            return true;
        } else {
            self::$lastError = self::$db->error;
            return false;
        }
    }

    public static function getAllMessages($lobbyId): array
    {
        return self::$db->get(
            self::TABLE_NAME,
            [
                'lobby_id' => $lobbyId,
            ]
        );
    }

    public static function deleteAllMessages($lobbyId): bool
    {
        $result = self::$db->delete(
            self::TABLE_NAME,
            [
                'lobby_id' => $lobbyId,
            ]
        );
        if ($result) {
            return true;
        } else {
            self::$lastError = self::$db->error;
            return false;
        }
    }

    public static function getLastError(): string
    {
        return self::$lastError;
    }
}
