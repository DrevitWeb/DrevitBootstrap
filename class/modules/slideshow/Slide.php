<?php

namespace modules\slideshow;

use basics\Database;

class Slide
{
    public $image;
    public $rank;
    public $description;
    public $slideshow;

    public $token;
    public $id;

    public function getImage(){return $this->image;}
    public function setImage($image)
    {
        Database::modify("slide", "image", $image, $this->token);
        $this->image = $image;
    }

    public function getRank(){return $this->rank;}
    public function setRank($rank)
    {
        Database::modify("slide", "rank", $rank, $this->token);
        $this->rank = $rank;
    }

    public function getDescription(){return $this->description;}
    public function setDescription($description)
    {
        Database::modify("slide", "description", $description, $this->token);
        $this->description = $description;
    }

    public function getSlideshow(){return $this->slideshow;}
    public function setSlideshow($slideshow)
    {
        Database::modify("slide", "slideshow", $slideshow, $this->token);
        $this->slideshow = $slideshow;
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
}