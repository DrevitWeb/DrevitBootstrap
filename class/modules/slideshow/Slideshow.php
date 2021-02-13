<?php

namespace modules\slideshow;
use basics\Database;
use basics\Utils;
use DirectoryIterator;

class Slideshow
{
    public $name;
    public $type;
    public $simple;

    public $token;
    public $id;

    public function getName(){return $this->name;}
    public function setName($name)
    {
        Database::modify("slideshows", "name", $name, $this->token);
        $this->name = $name;
    }

    public function getType(){return $this->type;}
    public function setType($type)
    {
        Database::modify("slideshows", "type", $type, $this->token);
        $this->type = $type;
    }

    public function isSimple(){return $this->simple;}
    public function setSimple($simple)
    {
        Database::modify("slideshows", "simple", $simple, $this->token);
        $this->simple = $simple;
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
        $slidesArray = Database::query("SELECT * FROM slides WHERE slideshow = ? ORDER BY rg", array($this->token))->fetchAll();
        return Utils::setObjects($slidesArray, "modules\slideshow\Slide");
    }

    public function addSlide($imgPath, $description=null)
    {
        if($this->isSimple())
        {
            $token = Utils::generateRandomString(30);
            $rank = Database::query("SELECT rg FROM slides WHERE slideshow = ? ORDER BY rg DESC LIMIT 1")->fetch();

            $rank = ($rank) ? $rank + 1 : 0;

            Database::query("INSERT INTO slides (image, rg, description, slideshow, token) VALUES (?,?,?,?,?)", array(
                $imgPath,
                $rank,
                $description,
                $this->token,
                $token
            ));

            $extension = preg_replace("/.*\.([a-z]*)/", "$1", $imgPath);
            $newFilePath = "res/img/slideshows/" . Utils::generateRandomString(10) . $extension;

            //Upload the file into the temp dir
            if (move_uploaded_file($imgPath, $newFilePath))
            {
                return true;
            }
        }
        else
        {
            $extension = preg_replace("/.*\.([a-z]*)/", "$1", $imgPath);
            $newFilePath = "res/img/" . $this->getToken() . "/" . Utils::generateRandomString(10) . $extension;

            //Upload the file into the temp dir
            if (move_uploaded_file($imgPath, $newFilePath))
            {
                return true;
            }
        }
    }

    public function display()
    {
        if($this->isSimple())
        {
            $dir = new DirectoryIterator("res/img/" . $this->getToken());
            $content = "<div class='slideshow " . $this->getType() . "'>";
            foreach ($dir as $fileinfo)
            {
                if (!$fileinfo->isDot())
                {
                    $content .= "<img src='res/img/" . $this->getToken() . "/" . $fileinfo . "'/>";
                }
            }
            $content .= "</div>";
            return $content;
        }
        else
        {
            $slides = $this->getSlides();
            $content = "<div class=\"slideshow " . $this->getType() . "\">";
            foreach ($slides as $slide)
            {
                $content .= "<img src='res/img/slideshows/".$slide->getImage()."' ".(($slide->getDescription() != null)?("desc='".$slide->getDescription()."'"):"").">";
            }
            $content .= "</div>";
            return $content;
        }
    }
}