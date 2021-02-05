<?php
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
        $hexas = json_decode($_POST["hexas"]);
        resolveCollisions($hexas);
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
                    if (\basics\Utils::circle_collision($hexa1, $hex))
                    {
                        $dx = abs($hexa1->left - $hex->left);
                        $dr = $hexa1->radius + $hex->radius;

                        if ($hexa1->top >= $hex->top)
                        {
                            $dy = $hexa1->top - $hex->top;

                            $ny = sqrt(abs($dr ^ 2 - $dx ^ 2 - $dy));
                            $hexa1->top = $hexa1->top + $ny + 10;
                        } else
                        {
                            $dy = $hex->top - $hexa1->top;

                            $ny = sqrt(abs($dr ^ 2 - $dx ^ 2 - $dy));
                            $hex->top = $hex->top + $ny + 10;
                        }
                    }
                }
            }
        }

        foreach ($hexas as &$hexa)
        {
            $found = false;
            if ($hexa->top + $hexa->radius * 2 >= $_POST["windows_height"])
            {
                for($i = 0; $i < $_POST["windows_width"] - $hexa->radius*2; $i+=10)
                {
                    for($j = 0; $j < $_POST["windows_height"] - $hexa->radius*2; $j+=10)
                    {
                        $hexa->left = $i;
                        $hexa->top = $j;
                        if(!isCollision($hexas))
                        {
                            $found = true;
                            break(2);
                        }
                    }
                }
                if(!$found)
                {
                    header("HTTP/1.1 500 Error stack overflow");
                    echo json_encode($hexas);
                    die();
                }

                /*try
                {
                    resolveCollisions($hexas);
                } catch (Exception $e)
                {
                    header("HTTP/1.1 500 Error stack overflow");
                    die();
                }*/
                $found = true;
            }
        }
}

function isCollision($hexas) {
    $collision = false;
    foreach ($hexas as $h1) {
        foreach ($hexas as $h2) {
            if(\basics\Utils::circle_collision($h1, $h2))
            {
                $collision = true;
            }
        }
    }

    return $collision;
}

function orderByLeft($a, $b) {
    //retourner 0 en cas d'égalité
    if ($a->left == $b->left) {
        return 0;
    } else if ($a->left < $b->left) {//retourner -1 en cas d’infériorité
        return -1;
    } else {//retourner 1 en cas de supériorité
        return 1;
    }
}