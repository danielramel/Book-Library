<?php
require_once "jsonstorage.php";
date_default_timezone_set('Europe/Budapest');
class Auth
{
    private $users;
    public function __construct()
    {
        $this->users = new JsonStorage("data/users.json");
    }
    public function register($user)
    {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        return $this->users->insert((object) $user);
    }
    public function user_exists($username)
    {
        $users = $this->users->filter(function ($user) use ($username) {
            return ((array) $user)['username'] === $username;
        });
        return count($users) >= 1;
    }
    public function login($user)
    {
        $_SESSION["user"] = $user;
        $user["last_login"] = date("Y-m-d H:i:s");
        $this->users->update(
            function ($elem) use ($user) {
                return $elem->username === $user["username"];
            },
            function (&$elem) use ($user) {
                $elem->last_login = $user["last_login"];
            }
        );
    }
    public function check_credentials($username, $password)
    {
        $users = $this->users->filter(function ($user) use ($username) {
            return ((array) $user)['username'] === $username;
        });
        if (count($users) === 1) {
            $user = (array) array_values($users)[0];
            return password_verify($password, $user["password"])
            ? $user
            : false;
        }
        return false;
    }
    public function is_authenticated()
    {
        return isset($_SESSION["user"]);
    }
    public function logout()
    {
        unset($_SESSION["user"]);
    }
}