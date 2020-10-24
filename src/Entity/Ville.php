<?php

namespace App\Entity;


use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VilleRepository::class)
 */
class Ville
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
     * @ORM\OneToMany(targetEntity=Commune::class, mappedBy="ville")
     */
    private $communes;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="villes")
     */
    private $region;

    /**
     * @ORM\Column(type="float")
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     */
    private $lng;

    public function __construct()
    {
        $this->communes = new ArrayCollection();
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
     * @return Collection|Commune[]
     */
    public function getCommunes(): Collection
    {
        return $this->communes;
    }

    public function addCommune(Commune $commune): self
    {
        if (!$this->communes->contains($commune)) {
            $this->communes[] = $commune;
            $commune->setVille($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): self
    {
        if ($this->communes->contains($commune)) {
            $this->communes->removeElement($commune);
            // set the owning side to null (unless already changed)
            if ($commune->getVille() === $this) {
                $commune->setVille(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getLibelle();
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

}
