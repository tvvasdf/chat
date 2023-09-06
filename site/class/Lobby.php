<?php

use Medoo\Medoo;

class Lobby
{
    private const DB_NAME = 'socnet';
    private const TABLE_NAME = 'lobbies';
    public static Medoo $db;
    private static string $lastError = '';

    private int $id;
    private string $code;
    private string $name;
    private bool $public;
    private array $invited;
    private array $banned;
    private array $admins;
    private array $users;

    public function __construct(string $code, int $id = 0)
    {
        if ($id) {
            $where = ['id' => $id];
        } else {
            $where = ['code' => $code];
        }

        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'id',
                'name',
                'code',
                'public',
                'serialized_users_id',
                'serialized_admins_id',
                'serialized_invited_id',
                'serialized_banned_id'
            ],
            $where
        )[0];

        $this->id = $result['id'];
        $this->name = $result['name'];
        $this->public = (bool) $result['public'];
        $this->code = $result['code'];
        $this->users = unserialize($result['serialized_users_id']);
        $this->admins = unserialize($result['serialized_admins_id']);
        $this->banned = unserialize($result['serialized_banned_id']);
        $this->invited = unserialize($result['serialized_invited_id']);
    }

    public function add(User $user): bool
    {
        if ($this->public || $invitedKey = array_search($user->getId(), $this->invited)) {
            $users = $this->users;
            $users[] = $user->getId();
            if (isset($invited) && isset($invitedKey)) {
                unset($invited[$invitedKey]);
            }
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_users_id' => serialize($users),
                    'serialized_invited_id' => isset($invited) ? serialize($invited) : serialize($this->invited)
                ],
                [
                    'id' => $this->id,
                ]
            );
            if (!$result) {
                self::$lastError = self::$db->error;
            }
        } else {
            self::$lastError = 'Лобби не публичное. Попросите администраторов пригласить вас.';
            return false;
        }
        return (bool) $result;
    }

    public function kick(User $user): bool
    {
        if (in_array($user->getId(), $this->users)) {
            unset($this->users[array_search($user->getId(), $this->users)]);
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_users_id' => serialize($this->users),
                ],
                [
                    'id' => $this->id,
                ]
            );
            if (!$result) {
                self::$lastError = self::$db->error;
            }
            return (bool) $result;
        }
        return false;
    }

    public function ban(User $user): bool
    {
        $this->kick($user);

        if (!in_array($user->getId(), $this->banned)) {
            $this->banned[] = $user->getId();
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_banned_id' => serialize($this->banned),
                ],
                [
                    'id' => $this->id,
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

    public function unban(User $user): bool
    {
        if (in_array($user->getId(), $this->banned)) {
            unset($this->banned[array_search($user->getId(), $this->banned)]);
            $result = self::$db->update(
                self::TABLE_NAME,
                [
                    'serialized_banned_id' => serialize($this->banned),
                ],
                [
                    'id' => $this->id,
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

    public static function create(array $fields): bool
    {
        if (is_numeric($fields['code'])) {
            self::$lastError = 'Символьный код не может содержать только цифры';
            return false;
        }
        if (trim($fields['code'])) {
            self::$lastError = 'Символьный код не содержит символов';
            return false;
        }
        if (strlen(trim($fields['code'])) < 3) {
            self::$lastError = 'Символьный код может содержать минимум 4 символа';
            return false;
        }
        if (strlen(trim($fields['code'])) > 16) {
            self::$lastError = 'Символьный код может содержать максимум 16 символов';
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

    public static function exists($id = false, $code = ''): bool
    {
        if (!$id && !$code) {
            return false;
        }
        if ($id) {
            $result = self::$db->select(
                self::TABLE_NAME,
                [
                    'id',
                ],
                [
                    'id' => $id,
                ]
            );
        } else {
            $result = self::$db->select(
                self::TABLE_NAME,
                [
                    'id',
                ],
                [
                    'code' => $code,
                ]
            );
        }
        return (bool) $result;
    }

    public static function erase($id): bool
    {
        $result = self::$db->delete(
            self::TABLE_NAME,
            [
                'id' => $id
            ]
        );
        if (!$result) {
            self::$lastError = self::$db->error;
        }
        return (bool) $result;
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
