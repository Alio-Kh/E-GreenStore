<?php

namespace App\Entity;

use App\Repository\TypeLivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use  Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=TypeLivraisonRepository::class)
 * @Vich\Uploadable()
 */
class TypeLivraison
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
      * @Vich\UploadableField(mapping="typeLivraisonPlace", fileNameProperty="image")
      */
      private $imageFile;


    /**
     * @ORM\Column(type="float")
     */
    private $frais;

    /**
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @ORM\OneToMany(targetEntity=Livraison::class, mappedBy="typeLivraison")
     */
    private $livraison;

    public function __construct()
    {
        $this->livraison = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $this->image ='img/TypeLivraison/'. $image;
        }

        return $this;
    }

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(float $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * @return Collection|Livraison[]
     */
    public function getLivraison(): Collection
    {
        return $this->livraison;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraison->contains($livraison)) {
            $this->livraison[] = $livraison;
            $livraison->setTypeLivraison($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraison->contains($livraison)) {
            $this->livraison->removeElement($livraison);
            // set the owning side to null (unless already changed)
            if ($livraison->getTypeLivraison() === $this) {
                $livraison->setTypeLivraison(null);
            }
        }

        return $this;
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

    public function __toString()
    {
        return (string) $this->getLibelle();
    }
  

}
