<?php


namespace basics;


class Session
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    static function set($index, $content) : void
    {

        $_SESSION[$index] = $content;
    }

    static function get($index)
    {
        if(isset($_SESSION[$index]))
        return $_SESSION[$index];
        return null;
    }

    static public function destroy($index) : void
    {
        unset($_SESSION[$index]);
    }

    static function setAlert($type, $message) : void
    {
        if(!isset($_SESSION["alerts"]) || empty($_SESSION["alerts"]))
        {
            $_SESSION["alerts"] = array();
        }
        if(!isset($_SESSION["alerts"][$type]) || empty($_SESSION["alerts"][$type]))
        {
            $_SESSION["alerts"][$type] = array();
        }
        array_push($_SESSION["alerts"][$type], $message);
    }

    static function getAlerts() : array
    {
        if(!isset($_SESSION["alerts"]) || empty($_SESSION["alerts"]))
        {
            $_SESSION["alerts"] = array();
        }
        if(!isset($_SESSION["alerts"]["errors"]))
        {
            $_SESSION["alerts"]["errors"] = array();
        }

        if(!isset($_SESSION["alerts"]["success"]))
        {
            $_SESSION["alerts"]["success"] = array();
        }
        return Session::get("alerts");
    }

    static function clearAlerts() : void
    {
        $_SESSION["alerts"] = array();
        $_SESSION["alerts"]["errors"] = array();
        $_SESSION["alerts"]["success"] = array();
    }
}