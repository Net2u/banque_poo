<?php

namespace App\Core;


// On importe PDO

use PDO;
use PDOException;

class Db extends PDO
{
    // Instance unique de la class
    private static $instance;

    // Information de connection
    private const DBHOST = 'localhost';
    private const DBUSER = 'root';
    private const DBPASS = '';
    private const DBHNAME = 'dbhname';
    private const CHARSET = 'UTF8';


    // design pattern singleton (constructeur privé qu'on ne peut pas instancier)
    private function __construct()
    {
        // dsn de connection
        $dsn = 'mysql:dbname=' . self::DBHNAME . ';host=' . self::DBHOST . ';charset=' . self::CHARSET ;

        // On appelle le constructeur de la class PDO
        try {
            parent::__construct($dsn, self::DBUSER, self::DBPASS);

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            //$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::);// écriture FETCH_ASSOC
            // écriture FETCH_OBJECT pour affichage et permettre $annonce->titre dans VIEWS/annonces/index.php
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // méthode static généré une instance(si il n'y a pas encore)
    // ou récupéré l'instance actuel si il y en a une

    // permet d'avoir une seule possibilité au niveau de l'instance
    
    public static function getInstance():self
    {
        if (self::$instance === null) {
            // fait un new de ma class elle même (aurait pu mettre new PDO() ou new Db() (puisqu'elle étend la class)
            self::$instance = new self();
        }
        // Quoi qu'il arrive on retourne l'instance elle même
        return self::$instance;
    }
}
