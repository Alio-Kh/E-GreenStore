<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="l emait que vous avez entré est dèja existé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Email
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min="8", minMessage="votre mot de passe doit faire minimum 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="vouz n'avez pas tapé le meme mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, cascade={"persist", "remove"})
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity=Admin::class, cascade={"persist", "remove"})
     */
    private $admin;

    /**
     * @ORM\OneToOne(targetEntity=Agriculteur::class, cascade={"persist", "remove"})
     */
    private $agriculteur;

    /**
     * @ORM\OneToMany(targetEntity=CommentaireBlog::class, mappedBy="user")
     */
    private $commentairesBlog;

    /**
     * @ORM\Column(type="string",unique=true, length=255, nullable=true)
     */
    private $github_id;

    /**
     * @ORM\Column(type="string",unique=true, length=255, nullable=true)
     */
    private $facebook_id;

    /**
     * @ORM\Column(type="string",unique=true, length=255, nullable=true)
     */
    private $google_id;

    public function __construct()
    { 
        $this->commentairesBlog = new ArrayCollection();
         
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return  $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    public function getAgriculteur(): ?Agriculteur
    {
        return $this->agriculteur;
    }

    public function setAgriculteur(?Agriculteur $agriculteur): self
    {
        $this->agriculteur = $agriculteur;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getUsername();
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
            $commentaireBlog->setUser($this);
        }

        return $this;
    }

    public function removeCommentaireBlog(CommentaireBlog $commentaireBlog): self
    {
        if ($this->commentairesBlog->contains($commentaireBlog)) {
            $this->commentairesBlog->removeElement($commentaireBlog);
            // set the owning side to null (unless already changed)
            if ($commentaireBlog->getUser() === $this) {
                $commentaireBlog->setUser(null);
            }
        }

        return $this;
    }

    public function getGithubId(): ?string
    {
        return $this->github_id;
    }

    public function setGithubId(?string $github_id): self
    {
        $this->github_id = $github_id;

        return $this;
    }

    /**
     * Get the value of facebook_id
     */ 
    public function getFacebook_id(): ?string
    {
        return $this->facebook_id;
    }

    /**
     * Set the value of facebook_id
     *
     * @return  self
     */ 
    public function setFacebook_id($facebook_id):self
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }

    /**
     * Get the value of google_id
     */ 
    public function getGoogle_id(): ?string
    {
        return $this->google_id;
    }

    /**
     * Set the value of google_id
     *
     * @return  self
     */ 
    public function setGoogle_id($google_id)
    {
        $this->google_id = $google_id;

        return $this;
    }
}
