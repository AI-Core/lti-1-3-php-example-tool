<?php

class Config
{
    protected static $config = [
        'cache' => [
            'duration' => [
                'default' => 3600, // 1 hour
                'min' => 60       // 1 minute
            ]
        ]
    ];

    public static function get(string $key)
    {
        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return null;
            }
            $value = $value[$key];
        }

        return $value;
    }
}
?>