<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use  Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 * @Vich\Uploadable()

 */
class Blog
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
    private $image;

    /**
      * @Vich\UploadableField(mapping="blogsPlace", fileNameProperty="image")
      */
      private $imageFile;


    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="blogs")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=Admin::class, inversedBy="blogs")
     */
    private $admin;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="blogs")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=CommentaireBlog::class, mappedBy="blog")
     */
    private $commentairesBlog;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->commentairesBlog = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        if ($this->imageFile) {
            $this->image ='img/blog/'. $image;
        }
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addBlog($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeBlog($this);
        }

        return $this;
    }

    /**
     * @return Collection|CommentaireBlog[]
     */
    public function getCommentairesBlog(): Collection
    {
        return $this->commentairesBlog;
    }

    public function addCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if (!$this->commentairesBlog->contains($commentaireBlog)) {
            $this->commentairesBlog[] = $commentaireBlog;
            $commentaireBlog->setBlog($this);
        }

        return $this;
    }

    public function removeCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if ($this->commentairesBlog->contains($commentaireBlog)) {
            $this->commentairesBlog->removeElement($commentaireBlog);
            // set the owning side to null (unless already changed)
            if ($commentaireBlog->getBlog() === $this) {
                $commentaireBlog->setBlog(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return (string) $this->getTitle();
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
