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
                'date' => date('Y-m-d H:i:s'),
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

    public static function getAllMessages(int $lobbyId = 0, bool $formatDate = true): array
    {
        $where['ORDER'] = 'date';
        if ($lobbyId) {
            $where['lobby_id'] = $lobbyId;
        }
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'id',
                'author_name',
                'author_login',
                'text',
                'is_pinned',
                'date',
                'lobby_id'
            ],
            $where
        );

        foreach ($result as $key => $item) {
            if ($formatDate) {
                $result[$key]['date'] = self::formatDate($item['date']);
            }
            $result[$key]['text'] = str_replace(PHP_EOL, '<br>', $item['text']);
        }

        return $result;
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

    private static function formatDate(string $date): string
    {
        $months = [
            'января', 'февраля', 'марта', 'апреля',
            'мая', 'июня', 'июля', 'августа',
            'сентября', 'октября', 'ноября', 'декабря',
        ];

        $current = date_create();
        $date = date_create($date);
        $interval = date_diff($current, $date);
        $message = match ($interval->days) {
            0 => 'Сегодня в',
            1 => 'Вчера в',
            2 => 'Позавчера в',
            default => $date->format('j ') . $months[(int) $date->format('n') - 1],
        };
        $message .= $date->format(' H:i');
        if ($interval->y) {
            $message .= $date->format(', Y г.');
        }
        return $message;
    }
}
