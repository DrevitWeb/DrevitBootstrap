<?php


namespace basics;

use PDO;
use PDOException;

class Database{
    //Instance de la BDD
    public static $pdo;
    private static $login = "root";
    private static $password = "rugsyakquiegep0";
    private static $database = "piste";
    private static $host = "localhost";

    /**
     * Créé une instance PDO et la configure
     *
     * @return PDO
     */
    public static function connect()
    {
        try
        {
            Database::$pdo = new PDO("mysql:dbname=". Database::$database.";host=".Database::$host, Database::$login, Database::$password);
            Database::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            Database::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            Database::$pdo->exec("SET CHARACTER SET utf8");
        }
        catch(PDOException $e)
        {
            echo "<h1>Erreur Base de donnée</h1>
                Une erreur s'est produite au niveau de la base de données. Veuillez reessayer plus tard.
                <br/>Si ce probleme persiste, veuillez contacter le webmaster du site dans la partie contact.";
            die();
        }
        return Database::$pdo;
    }

    /**
     * Fermer l'instance PDO
     *
     * @param $pdo
     */
    public static function disconnect(&$pdo)
    {
        $pdo = null;
    }

    /**
     * Envoie une requête à la BDD
     *
     * @param String $query Requête
     * @param array|bool $params Paramètres de la requête
     * @return PDOStatement
     */
    public static function query($query, $params = false)
    {
        $pdo = Database::connect();
        try
        {
            if($params)
            {
                $req = $pdo->prepare($query);
                $req->execute($params);
            }
            else
            {
                $req = $pdo->query($query);
            }

            Database::disconnect($pdo);
            return $req;
        }
        catch(PDOException $e)
        {
            Database::disconnect($pdo);
            echo "<br/>".$e->getMessage();
            die();
        }
    }

    /**
     * Modifier une valeur dans une table par token
     *
     * @param $table
     * @param $var
     * @param $value
     * @param $token
     */
    public static function modify($table, $var, $value, $token)
    {
        Database::query("UPDATE $table SET $var = ? WHERE token = '$token'", array($value));
    }

    /**
     * Modifier une valeur par identification utilisateur
     *
     * @param $table
     * @param $var
     * @param $value
     * @param $token
     */
    public static function modifyUser($table, $var, $value, $token)
    {
        if($value==NULL)
        {
            Database::query("UPDATE $table SET $var = NULL WHERE user = ?", array($token));
        }
        else
        {
            Database::query("UPDATE $table SET $var = ? WHERE user = ?", array($value, $token));
        }
    }

    /**
     * Envoie une valeur serialisée d'un objet dans la table trash et la suprimme de la BDD
     *
     * @param $table
     * @param $var_condition
     * @param $value
     */
    public static function trash($table, $var_condition, $value)
    {
        $req = Database::query("SELECT * FROM $table WHERE $var_condition = '$value'")->fetch();
        $trashed = addslashes(serialize($req));
        Database::query("INSERT INTO trash (text, delete_date) VALUES ('$trashed', '".time()."')");
        Database::query("DELETE FROM $table WHERE $var_condition = '$value'");
    }
}