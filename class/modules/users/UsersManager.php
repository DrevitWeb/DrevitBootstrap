<?php

namespace modules\users;

use basics\Database;
use basics\Session;
use basics\Utils;

class UsersManager
{
    public static function registerUser($pseudo, $password, $email, $birth_date, $gender)
    {
        $password = Utils::cryptPassword($pseudo, $password);
        $token = Utils::generateRandomString(50);
        $birth_date = strtotime($birth_date);
        Database::query
        (
            "INSERT INTO users (pseudo, email, password, avatar, gender, birth_date, sign_date, token, grade) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)"
            ,array($pseudo, $email, $password, "unknown", $gender, $birth_date, time(), $token, 0)
        );
        return $token;
    }

    public static function loginUser($pseudo, $password, $remember=false, $redirect="index", $redirect_error="user/login")
    {
        $password = Utils::cryptPassword($pseudo, $password);

        $userData = Database::query("SELECT * FROM users WHERE pseudo=? AND password=?", array($pseudo, $password))->fetch();
        if($userData)
        {
            $user = new User();
            Utils::setObject($user, $userData);

            if($remember)
            {
                self::setRemember($user);
            }

            Session::set("user", $user);
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function loginRemember($redirect="index", $redirect_error="user/login")
    {
        if(isset($_COOKIE['yonwa_remember']))
        {
            $cookie = $_COOKIE['yonwa_remember'];
            $password = explode(":", $cookie)[0];
            $pseudo = explode(':', $cookie)[1];

            $userData = Database::query("SELECT * FROM users WHERE pseudo=? AND password=?", array($pseudo, $password))->fetch();

            if ($userData)
            {
                $user = new User();
                Utils::setObject($user, $userData);
                self::setRemember($user);

                Session::set("user", $user);
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public static function setRemember($user)
    {
        setcookie("yonwa_remember", $user->getPassword().":".$user->getPseudo(), time()+3600*24*30); //Durée du cookie 1 mois
    }

    public static function getUserByToken($token)
    {
        $userData = Database::query("SELECT * FROM users WHERE token=?", array($token))->fetch();

        if ($userData)
        {
            $user = new User();
            Utils::setObject($user, $userData);
            return $user;
        }
        else
        {
            return null;
        }
    }

    public static function getUserByPseudo($pseudo)
    {
        $userData = Database::query("SELECT * FROM users WHERE pseudo=?", array($pseudo))->fetch();

        if ($userData)
        {
            $user = new User();
            Utils::setObject($user, $userData);
            return $user;
        }
        else
        {
            return null;
        }
    }

    public static function isUserConnected()
    {
        if(isset($_SESSION["user"]) && !empty($_SESSION["user"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function checkAuthorization($mini_grade)
    {
        if(!self::isUserConnected())
        {
            Utils::redirect("errors/403");
        }
        else if(self::getUserConnected()->getGrade() < $mini_grade)
        {
            Utils::redirect("errors/403");
        }
    }

    public static function getUserConnected()
    {
        if(isset($_SESSION["user"]) && !empty($_SESSION["user"]))
        {
            return $_SESSION["user"];
        }
        else
        {
            return null;
        }
    }

    public static function logout()
    {
        unset($_SESSION["user"]);
    }

    public static function hasFriendsRequests($token)
    {
        $friendsRequests = \basics\Database::query("SELECT * FROM friendship WHERE user2=?", array($token))->fetchAll();
        foreach ($friendsRequests as $request)
        {
            if($request->state == 0)
            {
                return true;
            }
        }

        return false;
    }
}