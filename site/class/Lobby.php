<?php

use Medoo\Medoo;

class Lobby
{
    private const TABLE_NAME = 'lobby';
    private static Medoo $db;
    private static string $lastError = '';

    public static function init(array $data)
    {
        self::$db = new Medoo($data);
    }

    public static function create(string $name, string $code, bool $public): bool
    {
        $users = serialize(
            [
                User::getId()
            ]
        );
        $result = self::$db->insert(
            self::TABLE_NAME, [
            'admins_id' => $users,
            'users_id' => $users,
            'name' => $name,
            'code' => $code,
            'public' => $public,
        ]);
        return (bool)$result->rowCount();
    }

    public static function join($lobbyId): bool
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'id',
                'public',
                'users_id',
                'invited_users_id'
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        if ($result['public'] || $invitedKey = array_search(User::getId(), $invited = unserialize($result['invited_users_id']))) {
            $users = unserialize($result['users_id']);
            $users[] = User::getId();
            if (isset($invited) && isset($invitedKey)) {
                unset($invited[$invitedKey]);
            }
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'users_id' => serialize($users),
                    'invited_users_id' => isset($invited) ? serialize($invited) : $result['invited_users_id']
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
            self::$lastError = 'Лобби не публичное. Попросите администраторов пригласить вас.';
            return false;
        }
    }

    public static function kick($lobbyId, $userId): bool
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'users_id',
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        $users = unserialize($result['users_id']);
        if (in_array($userId, $users)) {
            unset($users[array_search($userId, $users)]);
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'users_id' => serialize($users),
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
            return false;
        }
    }

    public static function ban($lobbyId, $userId): bool
    {
        self::kick($lobbyId, $userId);

        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'banned_id',
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        $banned = unserialize($result['banned_id']);
        if (!in_array($userId, $banned)) {
            $banned[] = $userId;
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'banned_id' => serialize($banned),
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
                'banned_id',
            ],
            [
                'id' => $lobbyId
            ]
        )[0];
        $banned = unserialize($result['banned_id']);
        if (in_array($userId, $banned)) {
            unset($banned[array_search($userId, $banned)]);
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'banned_id' => serialize($banned),
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
        if ($result) {
            return true;
        } else {
            self::$lastError = self::$db->error;
            return false;
        }
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

    public static function getLastError(): string
    {
        return self::$lastError;
    }
}
