<?php
class Cookie {

    public static function put($name, $value, $expiry) {
        // path is set to current domain
        if (setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }

    public static function exists($name) {
        return (isset($_cookie[$name])) ? true: false;
    }

    public static function get($name) {
        return $_COOKIE[$name];
    }

    public static function delete($name) {
        // to delete a cookie set it to past
        self::put($name, '', time() - 1);
    }
}