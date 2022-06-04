<?php

namespace App\Controllers;

use App\Core\form;
use App\Models\CompteOperationModel;
use App\Models\CompteModel;

class CompteController extends Controller
{
    /**
     * Affiche 1 compte client
     * @param int $id id du client
     * @return void
     */
    public function lire(int $id)
    {
        // On instancie le modele
        $soldes = new CompteOperationModel;

        // On va chercher 1 compte dans BD
        $solde = $soldes->find($id);

        $compteModel = new CompteModel;
        $comptes = $compteModel->find($id);

        // On envoie à la vue
        $this->render('compteClient/lire', compact('solde', 'comptes'));
        var_dump($comptes, $solde);
        //die;
    }


    /**
     * Déposer argent sur le compte
     * @param integer $id
     * @return void
     */
    public function deposer(int $id)
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {


            // On instancie le modele
            $soldes = new CompteOperationModel;

            // On va chercher 1 compte dans BD find id
            $newSolde = (object) $soldes->find($id);

            // Si compte operation n'existe pas, retour au login
            if (!isset($newSolde) or empty($newSolde)) {
                http_response_code(404);
                $_SESSION['erreur'] = "Ce compte n'existe pas";
                header('Location: /users/logout');
                exit;
            }

            // On vérifie compte operation compte_id appartient bien au user id
            if ($newSolde->{'compte_id'} !== $_SESSION['user']['id']) {

                $_SESSION['erreur'] = "Vous n'avez pas accès à cette page";
                header('Location: /users/logout');
                exit;
            }

            //Valide si tout les champs proposés sont remplis
            //(methode static pour intérogé sans obligé de l'instancier) 
            if (Form::validate($_POST, ['montant'])) {

                // On se protège contre les failles xss
                $montant = strip_tags($_POST['montant']);

                // On instancie
                $depot = new CompteOperationModel;

                //passe valeur dans deposer, set nouveau solde et set son id
                // $depot reçois les changements 
                $depot->setId($newSolde->id)
                    ->setSolde($newSolde->solde)->deposer($montant);

                // On enregistre les changements dans Bd

                $depot->update();

                // message session 
                $_SESSION['message'] = "Votre dépot a été enregistrée avec succès";
                header('location: /users/index');
                exit;

                // Ici le formulaire est complet

            } else {
                // Si le formulaire est incomplet
                $_SESSION['erreur'] = !empty($_POST) ? "Vous ne pouvez pas mettre ce montant" : '';
                $montant = isset($_POST['montant']) ? strip_tags($_POST['montant']) : '';
            }

            $form = new form;
            // htm5 pour nombre input type number, min '0.01'(valeur minimal accepté) avec des step de 1 sous
            $form->debutForm()
                ->ajoutLabelFor('montant', 'depot :')
                ->ajoutInput('number', 'montant', [
                    'id' => 'montant',
                    'class' => 'form-control',
                    'min' => '0.01',
                    'step' => '0.01',
                ])
                ->ajoutBouton('depot', ['class' => 'btn btn-primary'])
                ->finForm();

            // On envoie à la vue
            $this->render('compteClient/deposer', ['depotForm' => $form->create()]);

        } else {
            // l'utilisateur n'est pas connecté
            $_SESSION['erreur'] = "Vous devez vous connecter pour accéder à cette page";
            header('Location: /users/login');
            exit;
        }
    }

    /**
     * retire un montant du solde du compte
     * @param integer $id
     * @return void
     */
    public function retirer(int $id)
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) {

            // On instancie le modele
            $soldes = new CompteOperationModel;

            // On va chercher 1 compte dans BD find id
            $newSolde = (object) $soldes->find($id);

            // Si compte operation n'existe pas, retour au login
            if (!isset($newSolde) or empty($newSolde)) {
                http_response_code(404);
                $_SESSION['erreur'] = "Ce compte n'existe pas";
                header('Location: /users/logout');
                exit;
            }

            // On vérifie compte operation compte_id appartient bien au user id
            if ($newSolde->{'compte_id'} !== $_SESSION['user']['id']) {

                $_SESSION['erreur'] = "Vous n'avez pas accès à cette page";
                header('Location: /users/logout');
                exit;
            }

            //Valide si tout les champs proposés sont remplis
            //(methode static pour intérogé sans obligé de l'instancier) 
            if (Form::validate($_POST, ['montant'])) {

                // On se protège contre les failles xss
                $montant = strip_tags($_POST['montant']);

                // On instancie
                $retrait = new CompteOperationModel;

                //passe valeur dans retirer, set nouveau solde et set son id
                // $retrait reçois les changements 
                $retrait->setId($newSolde->id)
                    ->setSolde($newSolde->solde)->retirer($montant);

                // On enregistre les changements dans Bd

                $retrait->update();

                // message session 
                $_SESSION['message'] = "Votre retrait a été enregistrée avec succès";
                header('location: /users/index');
                exit;

                // Ici le formulaire est complet

            } else {
                // Si le formulaire est incomplet
                $_SESSION['erreur'] = !empty($_POST) ? "Vous ne pouvez pas mettre ce montant" : '';
                $montant = isset($_POST['montant']) ? strip_tags($_POST['montant']) : '';
            }

            $form = new form;
            // htm5 pour nombre input type number, min '0.01'(valeur minimal accepté) avec des step de 1 sous
            $form->debutForm()
                ->ajoutLabelFor('montant', 'retrait :')
                ->ajoutInput('number', 'montant', [
                    'id' => 'montant',
                    'class' => 'form-control',
                    'min' => '0.01',
                    'step' => '0.01',
                ])
                ->ajoutBouton('retrait', ['class' => 'btn btn-primary'])
                ->finForm();

            // On envoie à la vue
            $this->render('compteClient/retirer', ['retirerForm' => $form->create()]);

        } else {
            // l'utilisateur n'est pas connecté
            $_SESSION['erreur'] = "Vous devez vous connecter pour accéder à cette page";
            header('Location: /users/login');
            exit;
        }
    }
}
