<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaiementRepository::class)
 */
class Paiement
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
     * @ORM\OneToOne(targetEntity="Facture", cascade={"persist", "remove"})
     */
    private $facture;

    /**
     * @ORM\ManyToOne(targetEntity="CarteBancaire", inversedBy="paiements")
     */
    private $carteBancaire;

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

    /**
     * Get the value of facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set the value of facture
     *
     * @return  self
     */
    public function setFacture($facture)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get the value of carteBancaire
     */
    public function getCarteBancaire()
    {
        return $this->carteBancaire;
    }

    /**
     * Set the value of carteBancaire
     *
     * @return  self
     */
    public function setCarteBancaire($carteBancaire)
    {
        $this->carteBancaire = $carteBancaire;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getReference();
    }
}
