<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $reference;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\OneToOne(targetEntity="Paiement",cascade={"persist", "remove"})
     */
     private $paiement;

    /**
     * @ORM\OneToOne(targetEntity=Commande::class, cascade={"persist", "remove"})
     */
    private $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

  

    /**
     * Get the value of paiement
     */ 
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * Set the value of paiement
     *
     * @return  self
     */ 
    public function setPaiement($paiement)
    {
        $this->paiement = $paiement;

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

    public function __toString()
    {
        return (string) $this->getReference();
    }

}
