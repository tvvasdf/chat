<?php

use Medoo\Medoo;
class User
{
    private const TABLE_NAME = 'users';
    private static Medoo $db;
    private static string $login = '';
    private static string $passwordHash = '';

    private static string $lastError = '';

    public static function init(array $data): void
    {
        self::$db = new Medoo($data);
        if (isset($_COOKIE['login']) && $_COOKIE['login']) {
            self::$login = $_COOKIE['login'];
        }
        if (isset($_COOKIE['password_hash']) && $_COOKIE['password_hash']) {
            self::$passwordHash = $_COOKIE['password_hash'];
        }
    }
    public static function authorized(): bool
    {
        if (self::$login && self::$passwordHash) {
            $data = self::$db->select(
                self::TABLE_NAME,
                [
                    'login',
                ],
                [
                    'login' => self::$login,
                    'password_hash' => self::$passwordHash,
                ]
            );
            return (bool) $data;
        }
        return false;
    }

    public static function login(string $login, string $password): bool
    {
        $passwordHash = password_hash($password,  PASSWORD_DEFAULT);
        $data = self::$db->select(
            self::TABLE_NAME,
            [
                'password_hash',
            ],
            [
                'login' => $login,
            ]
        );
        if ($data && password_verify($password, $data[0]['password_hash'])) {
            setcookie('login', $login, time() + 3600 * 240);
            setcookie('password_hash', $data[0]['password_hash'], time() + 3600 * 240);
            return true;
        } else {
            return false;
        }
    }

    public static function register(array $data): bool
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'login',
            ],
            [
                'login' => $data['login'],
            ]
        );
        if ($result) {
            self::$lastError = 'Такой пользователь уже существует';
            return false;
        } else {
            $result = self::$db->insert(
                self::TABLE_NAME, [
                'login' => $data['login'],
                'password_hash' => password_hash($data['password'],  PASSWORD_DEFAULT),
                'name' => $data['name'],
                'access_rights' => 'E',
            ]);
            if ($result->rowCount()) {
                self::login($data['login'], $data['password']);
                return true;
            } else {
                return false;
            }
        }

    }

    public static function getAccess($needleRight = 'C'): string
    {
        $right = self::$db->select(
            self::TABLE_NAME,
            [
                'access_rights',
            ],
            [
                'login' => self::$login,
            ]
        );
        return $needleRight >= $right[0]['access_rights'];
    }

    public static function getId(): int
    {
        $data = self::$db->select(
            self::TABLE_NAME,
            [
                'id',
            ],
            [
                'login' => self::$login,
            ]
        );
        return (int) $data[0]['id'];
    }

    public static function getName($id): string
    {
        $data = self::$db->select(
            self::TABLE_NAME,
            [
                'name',
            ],
            [
                'id' => $id,
            ]
        );
        return (int) $data[0]['name'];
    }

    public static function getLastError(): string
    {
        return self::$lastError;
    }
}
