<?php

class User
{
    private $db;
    private $data;
    private $sessionName;
    private $cookieName;
    private $isLoggedIn;

    public function __construct($user = null)
    {
        $this->db = DB::getInstance();
        $this->sessionName = Config::get('session/session_name');
        $this->cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->sessionName)) {
                $user = Session::get($this->sessionName);

                if ($this->find($user)) {
                    $this->isLoggedIn = true;
                } else {

                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create($fields = [])
    {
        if (!$this->db->insert('users', $fields)) {
            throw new Exception('Warning! You account was not created');
        } else {

        }
    }

    public function update($fields = [], $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }

        if (!$this->db->update('users', $id, $fields)) {
            throw new Exception('Warning! Something go wrong,
             your account was not updated');
        }
    }

    public function find($user = null)
    {
        if ($user) {
            $field = (strpos($user, '@') != false) ? 'email' : 'id';
            $result = $this->db->get('users', [$field, '=', $user]);

            if ($result->count()) {
                $this->data = $result->first();
                return true;
            }
        }
        return false;
    }

    public function login($email = null, $login = null, $password = null,
                          $remember = false)
    {
        if (!$email && !$password && $this->exists()) {
            Session::put($this->sessionName, $this->data()->id);
        } else {
            $user = $this->find($email);

            if ($user) {
                if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->sessionName, $this->data()->id);

                    if ($remember) {
                        $hash = Hash::unique();
                        $hashCheck = $this->db->get('session', [
                            'user_id', '=', $this->data()->id
                        ]);

                        if (!$hashCheck->count()) {
                            $this->db->insert('session', [
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ]);
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }

                        Cookie::put($this->cookieName, $hash,
                            Config::get('remember/cookie_expire'));
                    }

                    return true;
                }
            }
        }

        return false;
    }

    public function logout()
    {
        $this->db->delete('session', ['user_id', '=', $this->data()->id]);

        Session::delete($this->sessionName);
        Cookie::delete($this->cookieName);
    }

    public function data()
    {
        return $this->data;
    }

    public function isLoggedIn()
    {
        return $this->isLoggedIn;
    }

    public function exists()
    {
        return (!empty($this->data)) ? true : false;
    }

    public function getCountries()
    {
        $countries = $this->db->query('SELECT * FROM app_countries');
        $count = [];
        foreach ($countries->result() as $country) {
            $count[$country->id] = $country->country_name;
        }
        return $count;

    }

    public function getLastId($table)
    {
        $res = $this->db->query("SELECT MAX(id) AS maxId FROM {$table}");
        return $res->first()->maxId;
    }

}