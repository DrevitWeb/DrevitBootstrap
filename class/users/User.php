<?php


namespace users;

use basics\Database;

class User
{
    public $pseudo;
    public $email;
    public $avatar;
    public $gender;
    public $birth_date;
    public $sign_date;
    public $last_seen;
    public $grade;

    private $token;
    public $id;

    public function getPseudo(){return $this->pseudo;}
    public function setPseudo($pseudo)
    {
        Database::modify("users", "pseudo", $pseudo, $this->token);
        $this->pseudo = $pseudo;
    }

    public function getEmail(){return $this->email;}
    public function setEmail($email)
    {
        Database::modify("users", "email", $email, $this->token);
        $this->email = $email;
    }

    public function getGender(){return $this->gender;}
    public function setGender($gender)
    {
        Database::modify("users", "gender", $gender, $this->token);
        $this->gender = $gender;
    }

    public function getBirthDate(){return $this->birth_date;}
    public function setBirthDate($birth_date)
    {
        Database::modify("users", "birth_date", $birth_date, $this->token);
        $this->birth_date = $birth_date;
    }

    public function getSignDate(){return $this->sign_date;}
    public function setSignDate($sign_date)
    {
        Database::modify("users", "sign_date", $sign_date, $this->token);
        $this->sign_date = $sign_date;
    }

    public function getGrade(){return $this->grade;}
    public function setGrade($grade)
    {
        Database::modify("users", "grade", $grade, $this->token);
        $this->grade = $grade;
    }

    public function getAvatar(){return $this->avatar;}
    public function setAvatar($avatar)
    {
        Database::modify("users", "avatar", $avatar, $this->token);
        $this->avatar = $avatar;
    }

    public function getLastSeen(){return $this->last_seen;}
    public function setLastSeen($last_seen)
    {
        Database::modify("users", "last_seen", $last_seen, $this->token);
        $this->last_seen = $last_seen;
    }

    public function getToken(){return $this->token;}
    public function setToken($token){$this->token = $token;}

    public function getId(){return $this->id;}
    public function setId($id){$this->id = $id;}
}