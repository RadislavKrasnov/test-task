<?php

class User
{
    private $db;

    public function __construct($user = null)
    {
        $this->db = DB::getInstance();
    }

    public function create($fields = [])
    {
        if (!$this->db->insert('users', $fields)) {
            throw new Exception('Warning! You account was not created');
        }
    }

}