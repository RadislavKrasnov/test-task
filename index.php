<?php
require_once 'core/init.php';

$users = DB::getInstance()->update('users', 3,[
    'real_name' => 'John',
    'email' => 'john@test.com',
    'login' => 'john',
//    'password' => '3',
//    'salt' => '3',
//    'birth_date' => '03/03/1990',
//    'country' => 'USA',
//    'timestamp' => '23355'
]);

//if (!$users->count()) {
//    echo 'No users';
//} else {
//    echo $users->first()->real_name;
//}

