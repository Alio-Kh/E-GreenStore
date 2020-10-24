<?php

namespace App\Entity;

use App\Repository\MarketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use  Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MarketRepository::class)
 * @Vich\Uploadable()
 */
class Market
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $image;

     /**
      * @Vich\UploadableField(mapping="marketPlace", fileNameProperty="image")
      */
      private $imageFile;

    

    /**
     * @ORM\OneToMany(targetEntity="Produit", mappedBy="market")
     */
    private $produits;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Agriculteur::class, inversedBy="markets")
     */
    private $agriculteur;

    

    public function __construct()
    {
        $this->agriculteurs = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->marketAgriculteurs = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        if ($this->imageFile) {
            $this->image ='img/markets/'. $image;
        }
        return $this;
        
   }

    public function getAgriculteur(): ?Agriculteur
    {
        return $this->agriculteur;
    }

    public function setAgriculteur(?Agriculteur $agriculteur): self
    {
        $this->agriculteur = $agriculteur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

   

 

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setMarket($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getMarket() === $this) {
                $produit->setMarket(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return (string) $this->getLibelle();
    }
  
     /**
     * Get the value of imageFile
     *
     * @return  mixed
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @param  mixed  $imageFile
     * @throws /Exception 
     */ 
    public function setImageFile( $imageFile): void
    {
        $this->imageFile = $imageFile;

    }


      
}
