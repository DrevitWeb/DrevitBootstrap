<?php


namespace modules\slideshow;


use basics\Database;
use basics\Utils;

class SlideshowManager
{
    public static function createSlideshow($name, $type)
    {
        $token = Utils::generateRandomString(30);
        Database::query("INSERT INTO slideshows (name, type, token) VALUES (?,?,?)", array(
            $name,
            $type,
            $token
        ));
        return Utils::setObject(Database::query("SELECT * FROM slideshows WHERE token=?", array($token))->fetch(), "modules\slideshow\Slideshow");
    }

    public static function getSlideshowByToken($token)
    {
        return Utils::setObject(Database::query("SELECT * FROM slideshows WHERE token=?", array($token))->fetch(), "modules\slideshow\Slideshow");
    }
}