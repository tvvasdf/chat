<?php

use Medoo\Medoo;

class User
{
    private const DB_NAME = 'socnet';
    private const TABLE_NAME = 'users';
    private array $userData = [
        'id' => 0,
        'login' => '',
        'name' => '',
    ];
    private bool $authorized = false;
    private static string $lastError = '';
    public static Medoo $db;

    public function __construct(string $login)
    {
        $result = self::$db->select(
            self::TABLE_NAME,
            [
                'id',
                'login',
                'name',
                'password_hash'
            ],
            [
                'login' => $login
            ]
        )[0];

        $this->userData = [
            'id' => $result['id'],
            'login' => $result['login'],
            'name' => $result['name'],
        ];
        $this->authorized = true;
        $_SESSION['user'] = [
            'login' => $result['login'],
            //'password' => $password,
        ];
    }

    public function getId(): int
    {
        return $this->userData['id'];
    }

    public function getName(): string
    {
        return $this->userData['name'];
    }

    public function getLogin(): string
    {
        return $this->userData['login'];
    }

    public function changeFields(array $fields): bool
    {
        $result = self::$db->update(
            self::TABLE_NAME,
            $fields,
            [
                'id' => $this->getId()
            ]
        );
        if (!$result) {
            self::$lastError = self::$db->error;
        }
        return (bool) $result;
    }

    public static function authorized(): bool
    {
        if (isset($_SESSION['user'])) {
            $user = new User($_SESSION['user']['login']);
            return $user->authorized;
        } else {
            return false;
        }
    }

    public static function getUser(): User
    {
        return new User($_SESSION['user']['login']);
    }

    public static function login(string $login, string $password): bool
    {
        $data = self::$db->select(
            self::TABLE_NAME,
            [
                'password_hash',
            ],
            [
                'login' => $login,
            ]
        )[0];
        if ($data && password_verify($password, $data['password_hash'])) {
            $_SESSION['user'] = [
                'login' => $login,
//                'password' => $password
            ];
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
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'name' => $data['name'],
                'privileges' => 1,
            ]);
            if ($result->rowCount()) {
                self::login($data['login'], $data['password']);
                return true;
            } else {
                return false;
            }
        }

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
                `login` VARCHAR(255) NOT NULL , 
                `name` VARCHAR(255) NOT NULL , 
                `password_hash` VARCHAR(255) NOT NULL , 
                `serialized_options` TEXT NOT NULL , 
                `privileges` INT NOT NULL , 
                PRIMARY KEY (`id`), UNIQUE (`login`)) ENGINE = InnoDB; 
        ');
    }
}
