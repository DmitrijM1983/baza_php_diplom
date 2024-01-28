<?php

class Redirect
{
    /**
     * @param string|int $location
     * @return void
     */
    public static function to(string|int $location): void
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 NOT Found.');
                        include 'includes/errors/404.php';
                        exit();
                        break;
                }
            }
            header('Location:' . $location);
        }
    }
}