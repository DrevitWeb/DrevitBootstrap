<?php
ini_set('display_errors', '1');

/*Type de contenu*/
header('content-type: text/html; charset=utf-8');

/*Définition du fuseau horaire sur Paris*/
date_default_timezone_set('Europe/Paris');

/*Autoloader de classes avec namespace*/
require "class/bootstrap.php";

/*Load la session*/
new \basics\Session();

/*Si la variable de page n'est pas définie, on redirige l'utilisateur vers l'accueil (index)*/
if (!isset($_GET["p"]))
{
    $_GET["p"] = "index";
}
/*Si la page spécifiée n'existe pas, on redirige l'utilisateur vers la page d'erreur 404 (not found)*/
else if (!file_exists("content/" . $_GET["p"] . ".php"))
{
    if(file_exists("content/" . $_GET["p"] . "/index.php"))
    {
        $_GET["p"] = $_GET["p"] . "/index";
    }
    else
    {
        $_GET["p"] = "errors/404";
    }
}

/*
 *Demarrage de la mise en cache :
 *Jusqu'à l'ob_end_clean(), aucune donnée n'est envoyée au navigateur,
 *tout le code html est mis en cache et peut être enregistré dans une variable.
*/
ob_start();
/*Inclusion de la page spécifiée (qui sera mise en cache et non envoyée au navigateur*/
include("content/" . $_GET["p"] . ".php");
/*Enregistrement du contenu mis en cache dans la variable $content*/
$content = ob_get_contents();
/*Arrêt de la mise en cache et effacement des données mises en cache*/
ob_end_clean();

/*Affichage du thème*/
include("layout.php");