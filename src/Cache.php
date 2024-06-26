<?php

class Cache
{
    protected static $cacheDir = __DIR__ . '/cache/';

    public static function get(string $key, $default = null)
    {
        $filePath = self::getCacheFilePath($key);
        if (file_exists($filePath)) {
            $data = file_get_contents($filePath);
            return unserialize($data);
        }
        return $default;
    }

    public static function put(string $key, $value, $duration)
    {
        $filePath = self::getCacheFilePath($key);
        $data = serialize($value);
        file_put_contents($filePath, $data);

        // Set expiration time
        $expirationTime = time() + $duration;
        touch($filePath, $expirationTime);
    }

    public static function has(string $key): bool
    {
        $filePath = self::getCacheFilePath($key);
        return file_exists($filePath);
    }

    public static function forget(string $key): void
    {
        $filePath = self::getCacheFilePath($key);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    protected static function getCacheFilePath(string $key): string
    {
        return self::$cacheDir . md5($key) . '.cache';
    }

    public static function initializeCacheDir()
    {
        if (!file_exists(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
    }
}

Cache::initializeCacheDir();
?>