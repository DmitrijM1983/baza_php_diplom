<?php

class Cookie
{
    /**
     * @param string $name
     * @param string $value
     * @param int $expires
     * @return bool
     */
    public static function put(string $name, string $value, int $expires): bool
    {
        if (setcookie($name, $value, time() + $expires, '/')) {
            return true;
        }
        return false;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function get(string $name): mixed
    {
        return $_COOKIE[$name];
    }

    /**
     * @param string $name
     * @return void
     */
    public static function delete(string $name): void
    {
        self::put($name, '',time()-1);
    }
}