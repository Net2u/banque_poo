<?php

// On importe les namespaces nécessaires
use App\Autoloader;
use App\Routes\Router;


// On définit une constante define 'ROOT';  root doit symboliser le dossier mes annonces, la racine du projet et non pas le dossier public 
//la fct dirname indique dossier parent dans lequel on se trouve
define('ROOT', dirname(__DIR__));

// On importe l'Autoloader // nous permet de charger les différente class qu'on va charger
require_once ROOT . '/Autoloader.php';

// Meth static register
Autoloader::register();

// On instancie Router (la class Router dans laquel la function start() va démarer l'application)
// donc Router est le router principal
$app = new Router();

// On démarre l'application
$app->start();

