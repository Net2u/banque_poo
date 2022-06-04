<?php

namespace App\Routes;

use App\Controllers\MainController;

/**
 * Router principal
 */

class Router
{
    public function start()
    {
        // On démarre la session
        session_start();

        // la réécriture url avec htaccess donc:
        // On récupère l'adresse URL
        $uri = $_SERVER['REQUEST_URI'];

        // section nettoie url et évite le duplicate content
        // On retire le "trailing slash" éventuel de l'url
        // On vérifie que uri n'est pas vide et si elle se termine par un / dans ce cas on l'enlèvera 
        if (!empty($uri) && $uri != '/' && $uri[-1] === '/') {
            // si oui Dans ce cas on enlève le /
            $uri = substr($uri, 0, -1);

            // On envoie une redirection permanente
            http_response_code(301);

            // On redirige vers l'URL sans le /
            header('Location: ' . $uri);
            exit;
        }
        // fin nettoyage url


        //On gère les paramètres d'url       
        // On explode (démonte) le Tableau $_GET et le $params
        //p=controleur/methode/paramètres
        //première partie du tableau le controler, le 2 la methode, 3 les paramètres
        $params = explode('/', $_GET['url']);

        // Si au moins 1 paramètre existe
        //pour crée le namespace complet
        // On récupère le nom du contrôleur à instancier
        //On fabrique le namespace:  On met une majuscule en la 1ère lettre en majuscule(ucfirst), et array_shift (enlève la première valeur d'un tableau) 
        //En mettant le namespace complet ceci permet dd'automatiser si non à chaque fois qu'on crée un controller il faudrais aller ajouter le bon USE controller dans le router
        // Ensuite on concaténation  "Controller"
        if ($params[0] != "") {
            $controller = '\\App\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';

            // On instancie le contrôleur(permet automatiser)
            $controller = new $controller();

            // On sauvegarde le 2ème paramètre dans $action si il existe, sinon index
            $action = isset($params[0]) ? array_shift($params) : 'index';

            if (method_exists($controller, $action)) {
                // Vérifie Si il reste des paramètres on les passe à la methode
                //on appelle la méthode call_user_func_array en envoyant les paramètres (sous forme de tableau)
                //va aller chercher la method action qui se retrouve dans la function controller et plus va passer les paramètres
                // donc la fonction call_user_func_array permet de démonté un tableau à la volé
                //sinon on l'appelle "à vide"
                (isset($params[0])) ? call_user_func_array([$controller, $action], $params) : $controller->$action();
            } else {
                // On envoie le code réponse 404 ))
                http_response_code(404);
                echo "La page recherchée n'existe pas";
            }
        } else {
            // Ici aucun paramètre n'est défini 
            // On instancie le contrôleur par défaut (page d'accueil)
            $controller = new MainController;

            // On appelle la méthode index
            $controller->index();
        }
    }
}
