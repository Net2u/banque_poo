<?php

namespace App\Controllers;

abstract class Controller
{
    protected  $template = 'default';
    protected $donnees = [];

    /**
     * Affiche une vue
     *
     * @param string $fichier
     * @param array $donnees
     * @return void
     */
    public function render(string $fichier, array $donnees = [])
    {
        // On extrait le contenu de $donnees
       
        extract($donnees);

        // On démare le buffer de sortie
        ob_start();
        // A partir de ce point toute sortie est conservée en mémoire

        // On crée le chemin vers la vue
        require_once ROOT . '/Views/' . $fichier . '.php';

        //Transfere le buffer dans la variable $contenu
        $contenu = ob_get_clean();

        //var_dump($contenu);

        // Template de page (utilise $contenu)
        require_once(ROOT . '/Views/' .$this->template.'.php');
    }
}
