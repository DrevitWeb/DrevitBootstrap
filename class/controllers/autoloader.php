<?php
/*
 *Fichier gerant les dependances
*/
spl_autoload_register('app_autoloader');

/**
 * Inclut automatiquement une classe
 *
 * @param String $class Classe
 */
function app_autoloader($class)
{
    $path = str_replace('\\', '/', $class);
    if (file_exists("../$path.php" ))
    {
        require_once("../$path.php");
    }
    else
    {
        \basics\Utils::debug_to_console("Autoloader -> Class ".addslashes($class)." not found at class/$path.php");
        die();
    }
}