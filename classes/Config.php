<?php


class Config
{
    public static function get($path = null) {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            foreach ($path as $piece) {
                if (isset($config[$piece])) {
                    $config = $config[$piece];
                }
            }
            return $config;
        }
        return false;
    }
}