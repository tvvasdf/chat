<?php

use Medoo\Medoo;

class Messages
{
    private const DB_NAME = 'socnet';
    private const TABLE_NAME = 'messages';
    public static Medoo $db;
    private static string $lastError = '';

    public static function add(User $user, int $lobbyId, string $text): bool
    {
        $result = self::$db->insert(
            self::TABLE_NAME,
            [
                'lobby_id' => $lobbyId,
                'text' => $text,
                'author_id' => $user->getId(),
                'author_name' => $user->getName(),
                'author_login' => $user->getLogin(),
            ]
        );
        if (!$result) {
            self::$lastError = self::$db->error;
        }
        return (bool) $result;
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
        if (!$result) {
            self::$lastError = self::$db->error;
        }
        return (bool) $result;
    }

    public static function pin($lobbyId, $messageId, $pinned = true): bool
    {
        $result = self::$db->update(
            self::TABLE_NAME,
            [
                'is_pinned' => $pinned
            ],
            [
                'lobby_id' => $lobbyId,
                'id' => $messageId
            ]
        );
        if (!$result) {
            self::$lastError = self::$db->error;
        }
        return (bool) $result;
    }

    public static function getAllMessages(int $lobbyId): array
    {
        return self::$db->select(
            self::TABLE_NAME,
            [
                'id',
                'author_name',
                'author_login',
                'text',
                'is_pinned',
                'date'
            ],
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
        if (!$result) {
            self::$lastError = self::$db->error;
        }
        return (bool) $result;
    }

    public static function getLastError(): string
    {
        return self::$lastError;
    }

    public static function install(): bool
    {
        return (bool) self::$db->query('
            CREATE TABLE `' . self::DB_NAME . '`.`' . self::TABLE_NAME . '` (
                `id` INT NOT NULL AUTO_INCREMENT , 
                `lobby_id` INT NOT NULL , 
                `author_id` INT NOT NULL , 
                `author_login` VARCHAR(255) NOT NULL , 
                `author_name` VARCHAR(255) NOT NULL , 
                `text` MEDIUMTEXT NOT NULL , 
                `is_pinned` BOOLEAN NOT NULL , 
                `date` DATETIME NOT NULL ,
                PRIMARY KEY (`id`)) ENGINE = InnoDB; '
        );
    }
}
