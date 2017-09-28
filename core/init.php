<?php
session_start();

$GLOBALS['config'] = [
    'mysql' => [
        'host' => '127.0.0.1',
        'username' => 'Radik',
        'password' => '1111',
        'db' => 'test_task'
    ],
    'remember' => [
        'cookie_name' => 'hash',
        'cookie_expire' => 2592000,
    ],
    'session' => [
        'session_name' => 'user',
        'token_name' => 'token'
    ]
];

spl_autoload_register(function ($class) {
   require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';