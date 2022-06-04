<?php

namespace App\Controllers;

use App\Core\form;
use App\Models\CompteModel;
use App\Models\CompteOperationModel;
use App\Models\UsersModel;

class AdminController extends Controller
{
    public function index()
    {
        // On vérifie si on est admin
        if ($this->isAdmin()) {
            $this->render('admin/index', [], 'admin');
        }
    }

    /**
     * Affiche la liste des comptes sous forme de tableau
     * @return void
     */
    public function comptes()
    {
        if ($this->isAdmin()) {
            $compteModel = new CompteModel;

            $compte = $compteModel->findAll();
            // transmet à la vue / le compact permet d'éviter un tableau associatif comptes => $comptes
            $this->render('admin/comptes', compact('compte'), 'admin');
        }
    }

    /**
     * Supprime un user son le compte client et compte opération si on est Admin
     *
     * @param int $id
     * @return void
     */
    public function supprimeCompte(int $id)
    {
        if ($this->isAdmin()) {

            $compte = new UsersModel;

            $compte->delete($id);

            header('location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Active ou désactive un compte (bouton swith)
     *
     * @param integer $id
     * @return void
     */
    public function activeAnnonce(int $id)
    {
        if ($this->isAdmin()) {
            $compteModel = new CompteModel;

            $compteArray = $compteModel->find($id);

            if ($compteArray) {
                $compteModel->hydrate($compteArray);
                // ternair en dessous
                // if($annonce->getActif()){
                //     $annonce->setActif(0);
                // }else {
                //     $annonce->setActif(1);
                // }
                //OU $annonce->setActif(!$annonce->getActif());      Sur un champs mySQL de type BOOLEAN
                //$annonce->setActif(intval(!$annonce->getActif()));  Sur un champs de type INT
                $comptes = $compteModel;
                $comptes->setActif($comptes->getActif() ? 0 : 1);
                //$comptes->setActif(intval(!$comptes->getActif()));
                $comptes->update();
            }
        }
    }


    /**
     * Vérifie si on est admin
     *
     * @return true
     */
    private function isAdmin()
    {
        // On vérifie si on est connecté et si " ROLE_ADMIN" est dans nos rôles
        if (isset($_SESSION['user']) && in_array('ROLE_ADMIN', $_SESSION['user']['roles'])) {
            // On est admin
            return true;
            $_SESSION['user'] = "vous êtes Admin";
            header('location: /');
            exit;
        } else {
            // On est pas Admin
            $_SESSION['erreur'] = "vous n'avez pas accès à cette zone";
            header('location: /');
            exit;
        }
    }

    /**
     * Inscription des utilisateurs (compte user)    *
     * @return void
     */
    public function register()
    {
        if ($this->isAdmin()) {

            // On vérifie si le formulaire est rempli  avec la méthide static validate (Valide si tout les champs proposés sont remplis)
            // donc si notre post contient les champs email et password
            if (Form::validate($_POST, ['email', 'password'])) {

                $user = new UsersModel;

                // vérifie si user existe dans BD avec son email
                $userArray = $user->findOneByEmail(strip_tags($_POST['email']));

                // Si l'utilisateur existe erreur
                if ($userArray) {
                    // On envoie un message de session
                    $_SESSION['erreur'] = 'L\'adresse e-mail et / ou le mot de passe est incorrect';
                    header('Location: /admin/register');
                    exit;
                }

                // user existe pas on crée
                // le formulaire est valide
                // On nettoie l'email et on chiffre le mot de passe
                $email = strip_tags($_POST['email']);
                $pass = password_hash($_POST['password'], PASSWORD_ARGON2I);

                /**
                 * On hydrate l'utilisateur 
                 */
                $user = new UsersModel;
                $user->setEmail($email)
                    ->setPassword($pass);

                // On stocke l'utilisateur dans Db
                $user->create();

                $user = new UsersModel;
                // On va chercher dans la base de données l'utilisateur avec l'email entrée
                $newUserEmail = $user->findOneByEmail($_POST['email']);

                // convertie l'objet STD en array
                $array = (array)$newUserEmail;
                //$array = get_object_vars($newUserEmail);

                // On affecte Le Id de $array provenant de newUserEmail à la Var de SESSION key de AdminModel
                $_SESSION['key'] = $array['id'];

                /** Message Session succès
                 * Envoie au formulaire pour crée le compte du client (fonction ajouter)
                 */
                $_SESSION['message'] = "Le compte user a été enregistrée avec succès";
                header('location: /admin/ajouter');
                exit;
            }
        }

        $form = new form;

        $form->debutForm()
            ->ajoutLabelFor('email', 'E-mail :')
            ->ajoutInput('email', 'email', ['class' => 'form-control', 'id' => 'email'])
            ->ajoutLabelFor('pass', 'Mot de passe :')
            ->ajoutInput('password', 'password', ['id' => 'pass', 'class' => 'form-control'])
            ->ajoutBouton('M\'inscrire', ['class' => 'btn btn-primary'])
            ->finForm();

        $this->render('admin/register', ['registerForm' => $form->create()]);
    }

    /**
     * Ajouter un compte client
     * @return void
     */
    public function ajouter()
    {
        // Convertie la var Session key 'string' en entier int
        $foreignKeyUser_id = intval($_SESSION['key']);

        //vérifie si existe
        if (isset($foreignKeyUser_id)) {

            //(On fait tojours le traitement avant de crée le formulaire -> new Form)
            // On vérifie si le formulaire est complet 

            //if (Form::validate($_POST, ['nom', 'prenom', 'ville'])) {
            if (Form::validate($_POST, ['nom', 'prenom', 'ville', 'solde'])) {
                // le formulaire est complet
                // On se protège contre les faille xss soit avec:
                // Strip_tags , htmlentities, htmlspecialchars

                $nom = strip_tags($_POST['nom']);
                $prenom = strip_tags($_POST['prenom']);
                $ville = strip_tags($_POST['ville']);

                $solde = strip_tags($_POST['solde']);

                // On instancie le modèle
                $client = new CompteModel;

                // On hydrate
                $client->setNom($nom)
                    ->setPrenom($prenom)
                    ->setVille($ville)
                    ->setUsers_id($foreignKeyUser_id);

                // On enregistre
                $client->create();

                // On instancie
                $soldeNewClient = new CompteOperationModel;

                //on modifie le solde du compte $foreignKeyUser_id
                $soldeNewClient->setSolde($solde)
                    ->setCompte_id($foreignKeyUser_id);

                // On enregistre dans Bd 
                $soldeNewClient->create();

                // On redirige
                $_SESSION['message'] = "Votre compte courant a été enregistrée avec succès";
                header('location: /admin');
                exit;

                // Ici le formulaire est complet
                
            } else {
                // Si le formulaire est incomplet
                $_SESSION['erreur'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                $nom = isset($_POST['nom']) ? strip_tags($_POST['nom']) : '';
                $prenom = isset($_POST['prenom']) ? strip_tags($_POST['prenom']) : '';
                $ville = isset($_POST['ville']) ? strip_tags($_POST['ville']) : '';
                $solde = isset($_POST['solde']) ? strip_tags($_POST['solde']) : '';
            }

            $form = new form;

            $form->debutForm('post', '#', ['enctype' => 'multipart/formdata'])
                ->ajoutLabelFor('nom', 'Nom :')
                ->ajoutInput('text', 'nom', [
                    'id' => 'nom',
                    'class' => 'form-control',
                ])
                ->ajoutLabelFor('prenom', 'prenom :')
                ->ajoutInput('text', 'prenom', [
                    'id' => 'prenom',
                    'class' => 'form-control',
                ])
                ->ajoutLabelFor('ville', 'ville :')
                ->ajoutInput('text', 'ville', [
                    'id' => 'ville',
                    'class' => 'form-control',
                ])
                ->ajoutLabelFor('solde', 'solde :')
                // valeur number pour nombre(et pas text) et step pour 2 décimal
                ->ajoutInput('number', 'solde', [
                    'id' => 'solde',
                    'class' => 'form-control',
                    'step' => '0.01',

                ])
                ->ajoutBouton('Ajouter', ['class' => 'btn btn-primary'])
                ->finForm();

            $this->render('admin/ajouter', ['form' => $form->create()]);
        } else {
            //l'utilisateur n'est pas connecté
            $_SESSION['erreur'] = "Vous devez vous connecter pour accéder à cette page";
            header('Location: /users/login');
            exit;
        }
    }

    /**
     * Déconnexion de l'utilisateur
     * @return exit 
     */
    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
