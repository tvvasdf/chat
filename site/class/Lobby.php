<?php

use Medoo\Medoo;

class Lobby
{
    private const DB_NAME = 'socnet';
    private const TABLE_NAME = 'lobbies';
    public static Medoo $db;
    private static string $lastError = '';


    public static function create(array $fields): bool
    {
        if (is_numeric($fields['code'])) {
            self::$lastError = 'Символьный код не может содержать только цифры';
            return false;
        }
        if (isset($fields['id'])) {
            unset($fields['id']);
        }
        if (!Lobby::exists(false, $fields['code'])) {
            $result = self::$db->insert(self::TABLE_NAME, $fields);
            return (bool) $result->rowCount();
        }
        self::$lastError = 'Лобби с таким символьным кодом уже существует';
        return false;
    }

    public static function join(int $lobbyId, User $user): bool
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'id',
                'public',
                'serialized_users_id',
                'serialized_invited_id'
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        if ($result['public'] || $invitedKey = array_search($user->getId(), $invited = unserialize($result['serialized_invited_id']))) {
            $users = unserialize($result['serialized_users_id']);
            $users[] = $user->getId();
            if (isset($invited) && isset($invitedKey)) {
                unset($invited[$invitedKey]);
            }
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_users_id' => serialize($users),
                    'serialized_invited_id' => isset($invited) ? serialize($invited) : $result['serialized_invited_id']
                ],
                [
                    'id' => $lobbyId,
                ]
            );
            if (!$result) {
                self::$lastError = self::$db->error;
            }
        } else {
            self::$lastError = 'Лобби не публичное. Попросите администраторов пригласить вас.';
        }
        return (bool) $result;
    }

    public static function kick($lobbyId, $userId): bool
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'serialized_users_id',
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        $users = unserialize($result['serialized_users_id']);
        if (in_array($userId, $users)) {
            unset($users[array_search($userId, $users)]);
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_users_id' => serialize($users),
                ],
                [
                    'id' => $lobbyId,
                ]
            );
            if (!$result) {
                self::$lastError = self::$db->error;
            }
        }
        return (bool) $result;
    }

    public static function ban($lobbyId, $userId): bool
    {
        self::kick($lobbyId, $userId);

        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'serialized_banned_id',
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        $banned = unserialize($result['serialized_banned_id']);
        if (!in_array($userId, $banned)) {
            $banned[] = $userId;
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_banned_id' => serialize($banned),
                ],
                [
                    'id' => $lobbyId,
                ]
            );
            if ($result) {
                return true;
            } else {
                self::$lastError = self::$db->error;
                return false;
            }
        } else {
            return true;
        }
    }

    public static function unban($lobbyId, $userId): bool
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'serialized_banned_id',
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        $banned = unserialize($result['serialized_banned_id']);
        if (in_array($userId, $banned)) {
            unset($banned[array_search($userId, $banned)]);
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_banned_id' => serialize($banned),
                ],
                [
                    'id' => $lobbyId,
                ]
            );
            if ($result) {
                return true;
            } else {
                self::$lastError = self::$db->error;
                return false;
            }
        } else {
            return true;
        }
    }

    public static function erase($lobbyId): bool
    {
        $result = self::$db->delete(
            self::TABLE_NAME,
            [
                'id' => $lobbyId
            ]
        );
        if (!$result) {
            self::$lastError = self::$db->error;
        }
        return (bool) $result;
    }

    public static function getId($code): int
    {
        return (int)self::$db->select(
            self::TABLE_NAME,
            [
                'id',
            ],
            [
                'code' => $code
            ]
        )[0]['id'];
    }

    public static function exists($id = false, string $code = ''): bool
    {
        if (!$id && !$code) return false;

        if ($id) {
            return (bool) self::$db->select(
                self::TABLE_NAME,
                [
                    'id',
                ],
                [
                    'id' => $id
                ]
            );
        }
        return (bool) self::$db->select(
            self::TABLE_NAME,
            [
                'id',
            ],
            [
                'code' => $code
            ]
        );
    }

    public static function getUserLobbies(User $user, bool $admin = false, $filter = []): array
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'id',
                'name',
                'code',
                'serialized_users_id',
                'serialized_admins_id',
                'serialized_invited_id',
                'serialized_banned_id'
            ],
            $filter
        );

        foreach ($result as $key => $lobby) {
            if (!in_array($user->getId(), unserialize($lobby['serialized_users_id']))) {
                unset($result[$key]);
            }

            if ($admin && !in_array($user->getId(), unserialize($lobby['serialized_admins_id']))) {
                unset($result[$key]);
            }
        }

        return $result;
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
                `name` VARCHAR(255) NOT NULL , 
                `code` VARCHAR(255) NOT NULL , 
                `public` BOOLEAN NOT NULL , 
                `serialized_users_id` TEXT NOT NULL , 
                `serialized_invited_id` TEXT NOT NULL ,
                `serialized_admins_id` TEXT NOT NULL , 
                `serialized_banned_id` TEXT NOT NULL , 
                PRIMARY KEY (`id`), UNIQUE (`code`)) ENGINE = InnoDB; 
        ');
    }
}
