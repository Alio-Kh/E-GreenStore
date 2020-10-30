<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use  Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 * @Vich\Uploadable()
 */
class Produit
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
    private $prixUnitaire;

    /**
     * @ORM\Column(type="float")
     */
    private $stock;

    /**
     * @ORM\Column(type="text" )
     */
    private $description;



    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $dateProduction;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $image;

    /**
     * @ORM\Column(type="date", nullable=false)
     *  @var \DateTime
     */
    private $dateAjout;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBio;

    /**
     * @ORM\Column(type="text" )
     */
    private $information;

    /**
     * @ORM\ManyToOne(targetEntity="Market", inversedBy="produits")
     */
    private $market;


    /**
     * @ORM\ManyToOne(targetEntity="Tva", inversedBy="produits")
     */
    private $tva;

    /**
     * @ORM\OneToMany(targetEntity="RecommendationProduit", mappedBy="produit")
     */
    private $recommedations;



    /**
     * @ORM\OneToMany(targetEntity="Vente", mappedBy="produit")
     */
    private $ventes;

    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="produit")
     */
    private $commentaires;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;

    /**
     * @Vich\UploadableField(mapping="produitPlace", fileNameProperty="image")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=LigneCommande::class, mappedBy="produit")
     */
    private $ligneCommandes;


    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unite;

    /**
     * @ORM\OneToOne(targetEntity=Promotion::class, cascade={"persist", "remove"})
     */
    private $promotion;


    public function __construct()
    {
        $this->recommedations = new ArrayCollection();
        $this->ventes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->ligneCommandes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getStock(): ?float
    {
        return $this->stock;
    }

    public function setStock(float $stock): self
    {
        $this->stock = $stock;

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

    public function getDateProduction(): ?\DateTimeInterface
    {
        return $this->dateProduction;
    }

    public function setDateProduction(\DateTimeInterface $dateProduction): self
    {
        $this->dateProduction = $dateProduction;

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
            $this->image = 'img/product/' . uniqid() . $image;
        }

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getIsBio(): ?bool
    {
        return $this->isBio;
    }

    public function setIsBio(bool $isBio): self
    {
        $this->isBio = $isBio;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(string $information): self
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Get the value of market
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Set the value of market
     *
     * @return  self
     */
    public function setMarket($market)
    {
        $this->market = $market;

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
     * Get the value of tva
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set the value of tva
     *
     * @return  self
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get the value of ventes
     */
    public function getVentes()
    {
        return $this->ventes;
    }

    /**
     * Set the value of ventes
     *
     * @return  self
     */
    public function setVentes($ventes)
    {
        $this->ventes = $ventes;

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

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * toString
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function addRecommedation(RecommendationProduit $recommedation): self
    {
        if (!$this->recommedations->contains($recommedation)) {
            $this->recommedations[] = $recommedation;
            $recommedation->setProduit($this);
        }

        return $this;
    }

    public function removeRecommedation(RecommendationProduit $recommedation): self
    {
        if ($this->recommedations->contains($recommedation)) {
            $this->recommedations->removeElement($recommedation);
            // set the owning side to null (unless already changed)
            if ($recommedation->getProduit() === $this) {
                $recommedation->setProduit(null);
            }
        }

        return $this;
    }





    public function addVente(Vente $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes[] = $vente;
            $vente->setProduit($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        if ($this->ventes->contains($vente)) {
            $this->ventes->removeElement($vente);
            // set the owning side to null (unless already changed)
            if ($vente->getProduit() === $this) {
                $vente->setProduit(null);
            }
        }

        return $this;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setProduit($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getProduit() === $this) {
                $commentaire->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getLigneCommandes(): Collection
    {
        return $this->ligneCommandes;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes[] = $ligneCommande;
            $ligneCommande->setProduit($this);
        }

        return $this;
    }

    public function removeLigneCommande(LigneCommande $ligneCommande): self
    {
        if ($this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes->removeElement($ligneCommande);
            // set the owning side to null (unless already changed)
            if ($ligneCommande->getProduit() === $this) {
                $ligneCommande->setProduit(null);
            }
        }

        return $this;
    }



    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    /** 
     *  @param User $user
     *  @return boolen
     */
    public function isLikedByClient(User $user): bool
    {
        foreach ($this->recommedations as $like) {
            if ($like->getClient() === $user->getClient()) return true;
        }
        return  false;
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
    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }
}
