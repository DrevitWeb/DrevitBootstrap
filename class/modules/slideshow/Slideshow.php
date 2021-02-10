<?php

namespace modules\slideshow;
use basics\Database;
use basics\Utils;

class Slideshow
{
    public $name;
    public $type;

    public $token;
    public $id;

    public function getName(){return $this->name;}
    public function setName($name)
    {
        Database::modify("slideshow", "name", $name, $this->token);
        $this->name = $name;
    }

    public function getType(){return $this->type;}
    public function setType($type)
    {
        Database::modify("slideshow", "type", $type, $this->token);
        $this->type = $type;
    }

    public function getToken(){return $this->token;}
    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getId(){return $this->id;}
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSlides()
    {
        $slidesArray = Database::query("SELECT * FROM slides WHERE slideshow = ?", array($this->token))->fetchAll();
        return Utils::setObjects($slidesArray, "modules\slideshow\Slide");
    }

    public static function addSlide($imgPath, $description)
    {
        $token = Utils::generateRandomString(30);
        $rank = Database::query("SELECT rank FROM slides WHERE slideshow = ? ORDER BY rank DESC LIMIT 1")->fetch()->next;

    }
}