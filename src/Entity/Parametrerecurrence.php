<?php

namespace App\Entity;

use App\Repository\ParametrerecurrenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParametrerecurrenceRepository::class)]
class Parametrerecurrence
{
    public function __construct()
    {
        $this->cloture = false;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?bool $cloture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $valeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

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

    public function getValeur(): ?\DateTimeInterface
    {
        return $this->valeur;
    }

    public function setValeur(\DateTimeInterface $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }
}
