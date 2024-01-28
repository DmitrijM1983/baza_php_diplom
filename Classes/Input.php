<?php

class Input
{
    /**
     * @param string $type
     * @return bool
     */
    public static function exists(string $type = 'post'): bool
    {
        return match ($type) {
            'post' => !empty($_POST),
            'get' => !empty($_GET),
            default => false,
        };
    }

    /**
     * @param string $item
     * @return string
     */
    public static function get(string $item): string
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } elseif (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }
}