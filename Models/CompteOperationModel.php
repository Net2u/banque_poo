<?php

namespace App\Models;

/**
 * Objet compte bancaire class ComptesClientModel extends Model
 */
class CompteOperationModel extends Model
{
    /**
     * Solde du compte
     * 
     * @var float
     */
    //protected float $id;
    protected float $solde;
    protected int $compte_id;
    protected $id;

    //public $table;
    public function __construct()
    {
        $this->table = 'compte_operation';
    }

    //  Accesseurs

    /**
     * Retourne le solde du compte
     *
     * @return float Solde du compte
     */
    public function getSolde(): float
    {
        return $this->solde;
    }

    /**
     * Modifie le solde du compte
     *
     * @param float $montant Montant du solde 
     * @return Compte Compte bancaire
     */
    public function setSolde(float $solde): self
    {
        $this->solde = $solde;
        return $this;
    }


    /**
     * Déposer argent sur le compte
     * @param float $montant Montant déposé
     * @return void
     */
    public function deposer(float $montant)
    {
        // On vérifie si montant est négatif ou positif
        //montant négatif 
        if ($montant < 0.01) {
            $_SESSION['montant-negatif'] = "Mettre un montant positif";
            header("location:" . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            // montant positif
            $this->solde += $montant;

            return $this;
        }
    }

    /**
     * retire un montant du solde du compte
     *
     * @param float $montant Montant à retirer
     * @return void
     */
    public function retirer(float $montant)
    {
        // On vérifie le montant est positif et que le solde est plus grand montant du retrait
        if ($montant >= 0.01 && $this->solde >= $montant) {
            $this->solde -= $montant;
            return $this;
        } else {
            //Montant invalide ou solde insufisant
            $_SESSION['montant-negatif'] = "Montant invalide ou solde insufisant";
            header('location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    /**
     * Get the value of compte_id
     */
    public function getCompte_id(): int
    {
        return $this->compte_id;
    }

    /**
     * Set the value of compte_id
     *
     * @return  self
     */
    public function setCompte_id(int $compte_id): self
    {
        $this->compte_id = $compte_id;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }
}
