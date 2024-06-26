<?php

class Cookie
{
    public static function get(string $name): ?string
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    public static function set(string $name, string $value, int $exp = 3600, array $options = []): void
    {
        $expire = time() + $exp;
        $path = $options['path'] ?? '/';
        $domain = $options['domain'] ?? '';
        $secure = $options['secure'] ?? false;
        $httponly = $options['httponly'] ?? true;
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public static function queue(string $name, string $value, int $exp = 60): void
    {
        self::set($name, $value, $exp * 60);
    }
}
?>