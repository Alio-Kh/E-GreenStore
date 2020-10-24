<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VenteRepository::class)
 */
class Vente
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateVente;

    /**
     * @ORM\Column(type="float")
     */
    private $qteVendu;

    /**
     * @ORM\Column(type="float")
     */
    private $prixVente;

    /**
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="ventes")
     */
    private $produit;

    /**
     * @ORM\Column(type="float")
     */
    private $reduction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->dateVente;
    }

    public function setDateVente(\DateTimeInterface $dateVente): self
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    public function getQteVendu(): ?float
    {
        return $this->qteVendu;
    }

    public function setQteVendu(float $qteVendu): self
    {
        $this->qteVendu = $qteVendu;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prixVente;
    }

    public function setPrixVente(float $prixVente): self
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get the value of produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set the value of produit
     *
     * @return  self
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;

        return $this;
    }

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(float $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getId();
    }

}
