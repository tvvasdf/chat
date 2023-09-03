<?php

class Main
{
    private static array $pagesExtensions = [
        'html', 'htm', 'php'
    ];

    private static string $publicFolder = '';
    private static string $templateFolder = '';
    private static string $dataFolder = '';
    private static string $pathError = 'error.php';

    private static array $pageProperties = [];

    public static function init($settings): void
    {
        if ($settings['debug'] === true) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
        self::$publicFolder = $settings['folder']['public'];
        self::$templateFolder = $settings['folder']['template'];
        self::$pathError = $settings['error_page'];

    }

    public static function showError(int $status): void
    {
        http_response_code($status);
        require self::getRoot('/') . self::$pathError;
    }

    public static function showTitle(): void
    {
        echo '##TITLE##';
        if (!isset(self::$pageProperties['##TITLE##'])) {
            self::$pageProperties['##TITLE##'] = '';
        }
    }

    public static function setTitle(string $value): void
    {
        self::$pageProperties['##TITLE##'] = $value;
    }

    public static function showProperty(string $name): void
    {
        echo '##' . strtoupper($name) . '##';
        if (!isset(self::$pageProperties['##' . strtoupper($name) . '##'])) {
            self::$pageProperties['##' . strtoupper($name) . '##'] = '';
        }
    }

    public static function setProperty(string $name, string $value): void
    {
        self::$pageProperties['##' . strtoupper($name) . '##'] = $value;
    }

    public static function includeHeader(): void
    {
        ob_start("Main::includeProperties");
        require self::getRoot('/' . self::$templateFolder . '/header.php');
    }

    public static function includeFooter(): void
    {
        require self::getRoot('/' . self::$templateFolder . '/footer.php');
        ob_end_flush();
    }

    public static function includeTemplateFile($path): void
    {
        if (file_exists(self::getRoot('/' . self::$templateFolder . $path))) {
            include self::getRoot('/' . self::$templateFolder . $path);
        }
    }

    public static function includePage(string $path = '', bool $show404 = true): void
    {
        if (!$path) {
            $paths = [];
            $info = pathinfo(self::getPath());
            if (isset($info['extension']) && isset($info['filename'])) {
                $paths[] = $info['dirname'] . '/' . $info['filename'] . '.' . $info['extension'];
            } else {
                foreach (self::$pagesExtensions as $ext) {
                    $paths[] = $info['dirname'] . $info['basename'] . '/index.' . $ext;
                }
            }
        } else {
            $paths[] = $path;
        }

        foreach ($paths as $path) {
            if (file_exists(self::getPublicRoot('/') . $path)) {
                require self::getPublicRoot('/') . $path;
                return;
            }
        }

        if ($show404 && file_exists(self::getRoot('/') . self::$pathError)) {
            self::showError(404);
        } else {
            echo 'Страница по пути ' . self::getPath() . ' не найдена! ';
        }
    }

    public static function getPath(): string
    {
        $path = explode('?', $_SERVER['REQUEST_URI']);
        return $path[array_key_first($path)];
    }

    public static function getPathArray(string $path = ''): array
    {
        if (!$path) {
            $path = self::getPath();
        }
        $array = array_diff(explode('/', $path), ['']);
        return array_values($array);
    }

    public static function getUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function getRoot($additional = ''): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . $additional;
    }

    public static function getPublicRoot($additional = ''): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/' . self::$publicFolder . $additional;
    }

    public static function getProperties(): array
    {
        return self::$pageProperties;
    }

    public static function clearBuffer(): void
    {
        ob_end_clean();
    }

    public static function sendJson(array $data): void
    {
        self::clearBuffer();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function redirect(string $url): void
    {
        if (headers_list()) {
            self::clearBuffer();
        }
        header('Location: ' . $url);
    }

    private static function includeProperties(string $buffer): string
    {
        return str_replace(array_keys(self::$pageProperties), array_values(self::$pageProperties), $buffer);
    }
}
