<?php

namespace App\Controllers;

use App\Core\form;
use App\Models\CompteModel;
use App\Models\CompteOperationModel;
use App\Models\UsersModel;

class UsersController extends Controller
{
    /**
     * Connection des utilisateurs   
     * @return void
     */
    public function login()
    {
        // On vérifie si le formulaire est valide
        if (Form::validate($_POST, ['email', 'password'])) {
            // le formulaire est valide
            // On va chercher dans la base de données l'utilisateur avec l'email entré
            $userModel = new UsersModel;
            $userArray = $userModel->findOneByEmail(strip_tags($_POST['email']));

            // Si l'utilisateur n'existe pas
            if (!$userArray) {
                // On envoie un message de session
                $_SESSION['erreur'] = 'Informations incorrectes contacter la banque';
                header('Location: /users/login');
                exit;
            }

            // l'utilisateur existe 
            $userModel->hydrate($userArray);
            $user = $userModel;

            // On vérifie le mot passe est correct
            if (password_verify($_POST['password'], $user->getPassword())) {
                // Le mot passe est bon 
                // On crée la session user
                $user->setSession();
                $_SESSION['message'] = "Connexion avec succès";

                //si var SESSION user role existe et si role dans tableau est admin redirige vers admin index
                // si non redirige user index 
                //admin index pour admin et users index pour compte du client
                if (isset($_SESSION['user']['roles']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {

                    header('location: /admin/index');
                    exit;
                } else {
                    header('location: /users/index');
                    exit;
                }
            } else {
                //mauvais mot de passe 
                $_SESSION['erreur'] = 'L\'adresse e-mail et / ou le mot de passe est incorrect';
                header('Location: /users/login');
                exit;
            }
        }

        // On instancie le formulaire
        $form = new Form;

        // On ajoute chacune des parties qui nous intéressent
        $form->debutForm()
            ->ajoutLabelFor('email', 'E-mail')
            ->ajoutInput('email', 'email', ['id' => 'email', 'class' => 'form-control'])
            ->ajoutLabelFor('pass', 'Mot de passe')
            ->ajoutInput('password', 'password', ['id' => 'pass', 'class' => 'form-control'])
            ->ajoutBouton('Me connecter', ['class' => 'btn btn-primary'])
            ->finForm();

        // On envoie le formulaire à la vue en utilisant notre méthode "create"
        $this->render('users/login', ['loginForm' => $form->create()]);
    }

    /**
     * Déconnexion de l'utilisateur
     * @return exit 
     */
    public function logout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['key']);
        $_SESSION['message'] = 'Déconnexion avec succès';
        header('Location: /');
        exit;
    }

    public function lire(int $id)
    {
        if (isset($_SESSION['user'])) {
            // On instancie le modele
            $soldes = new CompteOperationModel;

            // On va chercher 1 compte dans BD
            $solde = $soldes->find($id);

            $compteModel = new CompteModel;
            $compte = $compteModel->find($id);

            // On envoie à la vue
            $this->render('users/index', compact('solde', 'compte'));
            var_dump($compte, $solde);
            die;
        }
    }

    //Affiche compte du client
    public function index()
    {
        // On vérifie si user existe
        if (isset($_SESSION['user'])) {

            // instancie usermodel
            $user = new UsersModel;
            // On va chercher dans la base de données l'utilisateur avec l'email entrée
            $newUserEmail = $user->findOneByEmail($_SESSION['user']['email']);

            // convertie l'objet en array
            $array = (array)$newUserEmail;

            // On affecte Le Id de $array provenant de newUserEmail à la Var de SESSION key
            $_SESSION['key'] = $array['id'];
            $id = $_SESSION['key'];

            $soldes = new CompteOperationModel;

            // On va chercher 1 compte dans BD
            $solde = $soldes->find($id);

            $comptes = new CompteModel;

            // On va chercher 1 compte dans BD 
            $compte = $comptes->find($id);

            // On envoie à la vue
            $this->render('users/index', compact('solde', 'compte'));
        }
    }
}
