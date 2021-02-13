<?php

use basics\Maths;

session_start();

require "autoloader.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(isset($_GET["func"]))
{
    if(function_exists($_GET["func"]))
    {

        $_GET["func"]();
    }
    else
    {
        header("HTTP/1.1 401 Unauthorized function not found");
    }
}
else
{
    header("HTTP/1.1 401 Unauthorized no function provided");
}

function calcHexasPosition()
{
    if(isset($_POST["hexas"]))
    {
        $_SESSION["step"] = 0;
        $hexas = json_decode($_POST["hexas"]);
        resolveCollisions($hexas);
        resolveBorders($hexas);
        echo json_encode($hexas);
    }
}

function resolveCollisions($hexas) {
    while (isCollision($hexas))
    {
        foreach ($hexas as $hexa1)
        {
            foreach ($hexas as $hex)
            {
                if (Maths::circle_collision($hexa1, $hex))
                {
                    $dx = abs($hexa1->left - $hex->left);
                    $dr = $hexa1->radius + $hex->radius;

                    if ($hexa1->top >= $hex->top)
                    {
                        $dy = $hexa1->top - $hex->top;

                        $ny = sqrt(abs($dr ^ 2 - $dx ^ 2 - $dy));
                        $hexa1->top = $hexa1->top + $ny + 10;
                        $hexa1->step = $_SESSION["step"];
                    } else
                    {
                        $dy = $hex->top - $hexa1->top;

                        $ny = sqrt(abs($dr ^ 2 - $dx ^ 2 - $dy));
                        $hex->top = $hex->top + $ny + 10;
                        $hex->step = $_SESSION["step"];
                    }

                    $_SESSION["step"] = $_SESSION["step"] + 1;
                }
            }
        }
    }

    foreach ($hexas as &$hexa)
    {
        if ($hexa->top + $hexa->radius * 2 >= $_POST["windows_height"])
        {
            $found = false;
            do
            {
                $hexa->left = Maths::randInt(0, $_POST["windows_width"] - $hexa->radius * 2);
                $hexa->top = Maths::randInt(0, $_POST["windows_height"] - $hexa->radius * 2);

                if((!isCollision($hexas) && !$hexa->content && $hexa->left + $hexa->radius*2 < $_POST["windows_width"]) || (!isCollision($hexas) && $hexa->content && $hexa->left/$_POST["windows_width"]*100 >= 5 && $hexa->left/$_POST["windows_width"]*100 <= 90))
                {
                    $hexa->modified = true;
                    $found = true;
                    break(1);
                }
            }while(1);
            if(!$found)
            {
                header("HTTP/1.1 500 Error stack overflow");
                echo json_encode($hexas);
                die();
            }
        }
    }
}

function resolveBorders($hexas)
{
    foreach ($hexas as $hex)
    {
        if ($hex->left - $_POST["windows_width"]*0.06 < 0)
        {
            $hex->left = $_POST["windows_width"] * 0.06 - $hex->radius;
        }
    }

    while(isCollision($hexas))
    {
        resolveCollisions($hexas);
    }
}

function isCollision($hexas)
{
    $collision = false;
    foreach ($hexas as $h1) {
        foreach ($hexas as $h2) {
            if(Maths::circle_collision($h1, $h2))
            {
                $collision = true;
                break(2);
            }
        }
    }

    return $collision;
}