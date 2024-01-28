<?php

class Session
{
    /**
     * @param string $name
     * @param string $value
     * @return string
     */
    public static function put(string $name, string $value): string
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    /**
     * @param string $name
     * @return void
     */
    public static function delete(string $name): void
    {
        if (self::exists($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function get(string $name): mixed
    {
        return $_SESSION[$name] ?? '';
    }

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public static function setFlash(string $name, string $value): void
    {
        self::put($name, $value);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public static function getFlash(string $name): string|null
    {
        if (self::exists($name)) {
            $flash = self::get($name);
            unset($_SESSION[$name]);
            return $flash;
        }
        return null;
    }

}