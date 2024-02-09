<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
{
    public function __construct()
    {
        $this->cloture = false;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: 'boolean')]
    private ?string $cloture = null;

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

    public function getCloture(): ?string
    {
        return $this->cloture;
    }

    public function setCloture(string $cloture): static
    {
        $this->cloture = $cloture;

        return $this;
    }

    public function isCloture(): ?bool
    {
        return $this->cloture;
    }
}
