<?php

namespace App\Models;


/**
 * Modèle pour la table compte
 */
class CompteModel extends Model
{
    protected $id;
    protected $nom;
    protected $prenom;
    protected $ville;
    protected $actif;
    protected $users_id;

    public function __construct()
    {
        $this->table = 'compte';
    }

    /**
     * Obtenir la valeur de id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définir la valeur de id
     *
     * @return  self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nom
     */ 
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */ 
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of ville
     */ 
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set the value of ville
     *
     * @return  self
     */ 
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

        /**
     * Get the value of users_id
     */
    public function getUsers_id(): int
    {

        return $this->users_id;
    }

    /**
     * Set the value of users_id
     *
     * @return  self
     */
    public function setUsers_id(int $users_id):self
    {
        $this->users_id = $users_id;

        return $this;
    }

    /**
     * Obtenir la valeur de actif
     */
    public function getActif(): int
    {
        return $this->actif;
    }

    /**
     * Définir la valeur de actif
     *
     * @return  self
     */
    public function setActif(int $actif): self
    {
        $this->actif = $actif;

        return $this;
    }
}

