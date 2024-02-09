<?php

namespace App\Entity;

use App\Repository\BesoinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BesoinRepository::class)]
class Besoin
{
    public function __construct()
    {
        $this->cloture = false;
        $this->besoins = new ArrayCollection();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255,unique: true)]
    private ?string $libelle = null;

    #[ORM\Column(type: 'integer', length: 255)]
    private ?int $nombre = null;

    #[ORM\ManyToMany(targetEntity: 'Reservation', mappedBy: 'besoins')]
    #[ORM\JoinColumn(nullable: true)]
    private $besoins = null;




    #[ORM\Column(type: 'boolean')]
    private int|bool $cloture;

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

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): static
    {
        $this->nombre = $nombre;

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

    /**
     * @return Collection<int, Reservation>
     */
    public function getBesoins(): Collection
    {
        return $this->besoins;
    }

    public function addBesoin(Reservation $besoin): static
    {
        if (!$this->besoins->contains($besoin)) {
            $this->besoins->add($besoin);
            $besoin->addBesoin($this);
        }

        return $this;
    }

    public function removeBesoin(Reservation $besoin): static
    {
        if ($this->besoins->removeElement($besoin)) {
            $besoin->removeBesoin($this);
        }

        return $this;
    }
}
