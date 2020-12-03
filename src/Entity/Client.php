<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
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
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="client")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity="RecommendationProduit", mappedBy="client")
     */
    private $recommedations;

    /**
     * @ORM\OneToMany(targetEntity="CarteBancaire", mappedBy="client")
     */
    private $carteBancaires;

    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="client")
     */
    private $commentaires;
    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tele;

   

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->recommedations = new ArrayCollection();
        $this->carteBancaires = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
       
         
    }

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
     * Get the value of commandes
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * Set the value of commandes
     *
     * @return  self
     */
    public function setCommandes($commandes)
    {
        $this->commandes = $commandes;

        return $this;
    }

    /**
     * Get the value of recommedations
     */
    public function getRecommedations()
    {
        return $this->recommedations;
    }

    /**
     * Set the value of recommedations
     *
     * @return  self
     */
    public function setRecommedations($recommedations)
    {
        $this->recommedations = $recommedations;

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

    /**
     * Get the value of commentaires
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set the value of commentaires
     *
     * @return  self
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
            }
        }

        return $this;
    }

    public function addRecommedation(RecommendationProduit $recommedation): self
    {
        if (!$this->recommedations->contains($recommedation)) {
            $this->recommedations[] = $recommedation;
            $recommedation->setClient($this);
        }

        return $this;
    }

    public function removeRecommedation(RecommendationProduit $recommedation): self
    {
        if ($this->recommedations->contains($recommedation)) {
            $this->recommedations->removeElement($recommedation);
            // set the owning side to null (unless already changed)
            if ($recommedation->getClient() === $this) {
                $recommedation->setClient(null);
            }
        }

        return $this;
    }

    public function addCarteBancaire(carteBancaire $carteBancaire): self
    {
        if (!$this->carteBancaires->contains($carteBancaire)) {
            $this->carteBancaires[] = $carteBancaire;
            $carteBancaire->setClient($this);
        }

        return $this;
    }

    public function removeCarteBancaire(carteBancaire $carteBancaire): self
    {
        if ($this->carteBancaires->contains($carteBancaire)) {
            $this->carteBancaires->removeElement($carteBancaire);
            // set the owning side to null (unless already changed)
            if ($carteBancaire->getClient() === $this) {
                $carteBancaire->setClient(null);
            }
        }

        return $this;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setClient($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getClient() === $this) {
                $commentaire->setClient(null);
            }
        }

        return $this;
    }

    

     
  

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTele(): ?string
    {
        return $this->tele;
    }

    public function setTele(string $tele): self
    {
        $this->tele = $tele;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getReference();
    }

    

 
}
