<?php

namespace App;

class App{

    const DB_SGBD = 'mysql'; // systeme gestion de base de données
    const DB_HOST = 'localhost';
    const DB_DATABASE = 'tp_blog';
    const DB_USER = 'root';
    CONST DB_PWD = '';
    public static $db; // App:db sera notre instance PDO

    //méthode connexion à la BDD
    public static function dbConnect() : void
    {
        try{
            // DSN: mysql:host=localhost;dbname=tp_blog;
            self::$db = new \PDO (
                self::DB_SGBD . ':host=' . self::DB_HOST . ';dbname=' . self::DB_DATABASE . ';',
                self::DB_USER,
                self::DB_PWD,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                ]
            );
            // antislash car appel d'une class faisant partie de l'espace globale (php idem for datetime)
        } catch(\Exception $e) {
            die('Erreur de la connexion à la base de données :' . $e->getMessage());
        }

    } // function dbConnect


}// fin class app
