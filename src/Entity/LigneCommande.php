<?php

namespace App\Entity;

use App\Repository\LigneCommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneCommandeRepository::class)
 */
class LigneCommande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="float")
     */
    private $qteCommande;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="ligneCommandes")
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="ligneCommandes")
     */
    private $produit;
  
    
    public function __construct()
    {
        
        
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getQteCommande(): ?float
    {
        return $this->qteCommande;
    }

    public function setQteCommande(float $qteCommande): self
    {
        $this->qteCommande = $qteCommande;

        return $this;
    }

    /**
     * Get the value of produits
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set the value of produits
     *
     * @return  self
     */
    public function setProduits($produits)
    {
        $this->produits = $produits;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }
 
    public function __toString()
    {
        return (string) $this->getId();
    }
 
  
}
