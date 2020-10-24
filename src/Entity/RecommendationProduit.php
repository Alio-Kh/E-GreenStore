<?php

namespace App\Entity;

use App\Repository\RecommendationProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecommendationProduitRepository::class)
 */
class RecommendationProduit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

 

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="recommedations")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="recommedations")
     */
    private $produit;

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString()
    {
        return (string) $this->getId();
    }

}
