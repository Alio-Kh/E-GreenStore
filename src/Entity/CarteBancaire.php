<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarteBancaireRepository;

/**
 * @ORM\Entity(repositoryClass=CarteBancaireRepository::class)
 */
class CarteBancaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $numroCarte;

    /**
     * @ORM\Column(type="date")
     */
    private $dateExpiration;

    /**
     * @ORM\OneToMany(targetEntity="Paiement", mappedBy="carteBancaire")
     */
    private $paiements;

    /**
     * @ORM\ManyToOne(targetEntity="TypeCarte", inversedBy="carteBancaires")
     */
    private $typeCarte;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="carteBancaires")
     */
    private $client;
 
    public function __construct()
    {
        $this->paiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumroCarte(): ?string
    {
        return $this->numroCarte;
    }

    public function setNumroCarte(string $numroCarte): self
    {
        $this->numroCarte = $numroCarte;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    /**
     * Get the value of paiements
     */
    public function getPaiements()
    {
        return $this->paiements;
    }

    /**
     * Set the value of paiements
     *
     * @return  self
     */
    public function setPaiements($paiements)
    {
        $this->paiements = $paiements;

        return $this;
    }

    /**
     * Get the value of client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @return  self
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the value of typeCarte
     */
    public function getTypeCarte()
    {
        return $this->typeCarte;
    }

    /**
     * Set the value of typeCarte
     *
     * @return  self
     */
    public function setTypeCarte($typeCarte)
    {
        $this->typeCarte = $typeCarte;

        return $this;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements[] = $paiement;
            $paiement->setCarteBancaire($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->contains($paiement)) {
            $this->paiements->removeElement($paiement);
            // set the owning side to null (unless already changed)
            if ($paiement->getCarteBancaire() === $this) {
                $paiement->setCarteBancaire(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getNumroCarte();
    }
}
