<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte avec cet email existe déjà.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct()
    {
        $this->isverified = false;
        $this->datecreation=new \DateTime();
        $this->cloture=false;
        $this->onlypseudo=false;
        $this->Rolesusers = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length:255 , nullable: true)]
    private $nom;


    #[ORM\Column(type: 'string', length:255 , nullable: true)]
    private $telephone;

    #[ORM\Column(type : 'boolean')]
    private $isverified;


    #[ORM\Column(type : 'boolean')]
    private $cloture;

    // le token qui servira lors de l'oubli de mot de passe
    #[ORM\Column(type: 'string', length:255 , nullable: true)]
    protected $resetToken;

    #[ORM\Column(type: 'datetime')]
    private $datecreation = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\ManyToMany(targetEntity: 'Rolesuser', inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    private  $Rolesusers = null;

    #[Assert\Length( max: 4096,  maxMessage: 'Taille de champs maximale dépassée (4096 caractères).')]
    #[Assert\Length( min: 8, max: 4096,  maxMessage: 'Taille de champs maximale dépassée (4096 caractères).',minMessage: 'Votre mot de passe doit contenir au moins 8 caractères.')]
    private $plainPassword;

    #[ORM\Column(type: 'string', length:64 )]
    private $password;

    /**
     * @var string The hashed password
     */
   /* #[ORM\Column]
    private ?string $password = null;*/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function isIsverified(): ?bool
    {
        return $this->isverified;
    }

    public function setIsverified(bool $isverified): static
    {
        $this->isverified = $isverified;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): static
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }


    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): static
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function isCloture(): ?bool
    {
        return $this->cloture;
    }

    public function setCloture(bool $cloture): static
    {
        $this->cloture = $cloture;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }


    /**
     * @return Collection<int, Rolesuser>
     */
    public function getRolesusers(): Collection
    {
        return $this->Rolesusers;
    }

    public function addRolesuser(Rolesuser $rolesuser): static
    {
        if (!$this->Rolesusers->contains($rolesuser)) {
            $this->Rolesusers->add($rolesuser);
        }

        return $this;
    }

    public function removeRolesuser(Rolesuser $rolesuser): static
    {
        $this->Rolesusers->removeElement($rolesuser);

        return $this;
    }
}
