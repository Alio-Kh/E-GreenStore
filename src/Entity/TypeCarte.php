<?php

namespace App\Entity;

use App\Repository\TypeCarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeCarteRepository::class)
 */
class TypeCarte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="CarteBancaire", mappedBy="typeCarte")
     */
    private $carteBancaires;

    public function __construct()
    {
        $this->carteBancaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get the value of carteBancaires
     */
    public function getCarteBancaires()
    {
        return $this->carteBancaires;
    }

    /**
     * Set the value of carteBancaires
     *
     * @return  self
     */
    public function setCarteBancaires($carteBancaires)
    {
        $this->carteBancaires = $carteBancaires;

        return $this;
    }

    public function addCarteBancaire(CarteBancaire $carteBancaire): self
    {
        if (!$this->carteBancaires->contains($carteBancaire)) {
            $this->carteBancaires[] = $carteBancaire;
            $carteBancaire->setTypeCarte($this);
        }

        return $this;
    }

    public function removeCarteBancaire(CarteBancaire $carteBancaire): self
    {
        if ($this->carteBancaires->contains($carteBancaire)) {
            $this->carteBancaires->removeElement($carteBancaire);
            // set the owning side to null (unless already changed)
            if ($carteBancaire->getTypeCarte() === $this) {
                $carteBancaire->setTypeCarte(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getLibelle();
    }

}
