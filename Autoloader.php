<?php

namespace App;

class Autoloader
{
    static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }

    static function autoload($class)
    {
        // On récupère dans  $class la totalité du namespace de la classe concernée ex(App\Models\CompteModel)
        //On retire, effacer (App\) et pour conserver (Models\CompteModel) pour avoir le chemin accès à nos fichier
        // et (pour échaper l'anti-slash mettre 2 \\)
        // La constante magique NAMESPACE nous indique le namespace dans lequel on se trouve
        // On va donc retirer App\ de notre namespace en mettant namespace et antislache derrière \
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
        
        // On remplace les \ par des / (pour écrire lechemin acces à nos fichier)
        $class = str_replace('\\', '/', $class);
        // finalement ajoute .php à notre chemin
        $fichier = __DIR__ . '/' . $class . '.php';

        // On vérifie si le fichier($class.php) existe pour en suite le charger
        if(file_exists($fichier)) {
            require_once $fichier;
        }
    }
}
