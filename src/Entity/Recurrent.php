<?php

namespace App\Entity;

use App\Repository\RecurrentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\TextType;

#[ORM\Entity(repositoryClass: RecurrentRepository::class)]
class Recurrent
{
    public function __construct()
    {
        $this->salles = new ArrayCollection();
        $this->objectifs = new ArrayCollection();
        $this->besoins = new ArrayCollection();
        $this->datecreation=new \DateTime();
        $this->cloture=false;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type : 'string', length: 255)]
    private ?string $titre = null;

    #[ORM\ManyToOne(targetEntity: 'Collectif')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Collectif $collectif = null;

    #[ORM\ManyToOne(targetEntity: 'Typereservation')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Typereservation $typereservation = null;

    #[ORM\ManyToOne(targetEntity: 'Statutevent')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Statutevent $statutevent = null;

    #[ORM\ManyToOne(targetEntity: 'Mailreservation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?\App\Entity\Mailreservation $mailreservation = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    private ?string $personnepresente = null;

    #[ORM\Column(type: 'integer')]
    private ?int $nbpersonne = null;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\User $refbase = null;

    #[ORM\ManyToOne(targetEntity: 'Periodicite')]
    #[ORM\JoinColumn(nullable: false)]
    private ?\App\Entity\Periodicite $periodicite = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: 'Salle', inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    private  $salles = null;

    #[ORM\ManyToMany(targetEntity: 'Objectif', inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    private $objectifs = null;

    #[ORM\ManyToMany(targetEntity: 'Besoin', inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    private  $besoins = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    private ?string $autrebesoin = null;

    #[ORM\Column (type: 'boolean', nullable: false)]
    private ?bool $gratuit = null;

    #[ORM\Column (type: 'boolean', nullable: false)]
    private ?bool $cloture = null;

    #[ORM\ManyToOne(targetEntity: 'Besoinbar')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Besoinbar $besoinbar = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(nullable: false)]
    private ?\App\Entity\User $reserveby = null;


    #[ORM\Column(type: 'datetime', nullable: false)]
    private  $datedebut = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private  $datefin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPersonnepresente(): ?string
    {
        return $this->personnepresente;
    }

    public function setPersonnepresente(?string $personnepresente): static
    {
        $this->personnepresente = $personnepresente;

        return $this;
    }

    public function getNbpersonne(): ?int
    {
        return $this->nbpersonne;
    }

    public function setNbpersonne(int $nbpersonne): static
    {
        $this->nbpersonne = $nbpersonne;

        return $this;
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

    public function getAutrebesoin(): ?string
    {
        return $this->autrebesoin;
    }

    public function setAutrebesoin(?string $autrebesoin): static
    {
        $this->autrebesoin = $autrebesoin;

        return $this;
    }

    public function isGratuit(): ?bool
    {
        return $this->gratuit;
    }

    public function setGratuit(bool $gratuit): static
    {
        $this->gratuit = $gratuit;

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


    public function getCollectif(): ?Collectif
    {
        return $this->collectif;
    }

    public function setCollectif(?Collectif $collectif): static
    {
        $this->collectif = $collectif;

        return $this;
    }

    public function getTypereservation(): ?Typereservation
    {
        return $this->typereservation;
    }

    public function setTypereservation(?Typereservation $typereservation): static
    {
        $this->typereservation = $typereservation;

        return $this;
    }

    public function getStatutevent(): ?Statutevent
    {
        return $this->statutevent;
    }

    public function setStatutevent(?Statutevent $statutevent): static
    {
        $this->statutevent = $statutevent;

        return $this;
    }

    public function getMailreservation(): ?Mailreservation
    {
        return $this->mailreservation;
    }

    public function setMailreservation(?Mailreservation $mailreservation): static
    {
        $this->mailreservation = $mailreservation;

        return $this;
    }

    public function getRefbase(): ?User
    {
        return $this->refbase;
    }

    public function setRefbase(?User $refbase): static
    {
        $this->refbase = $refbase;

        return $this;
    }

    public function getPeriodicite(): ?Periodicite
    {
        return $this->periodicite;
    }

    public function setPeriodicite(?Periodicite $periodicite): static
    {
        $this->periodicite = $periodicite;

        return $this;
    }


    /**
     * @return Collection<int, Salle>
     */
    public function getSalles(): Collection
    {
        return $this->salles;
    }

    public function addSalle(Salle $salle): static
    {
        if (!$this->salles->contains($salle)) {
            $this->salles->add($salle);
        }

        return $this;
    }

    public function removeSalle(Salle $salle): static
    {
        $this->salles->removeElement($salle);

        return $this;
    }

    /**
     * @return Collection<int, Objectif>
     */
    public function getObjectifs(): Collection
    {
        return $this->objectifs;
    }

    public function addObjectif(Objectif $objectif): static
    {
        if (!$this->objectifs->contains($objectif)) {
            $this->objectifs->add($objectif);
        }

        return $this;
    }

    public function removeObjectif(Objectif $objectif): static
    {
        $this->objectifs->removeElement($objectif);

        return $this;
    }

    /**
     * @return Collection<int, Besoin>
     */
    public function getBesoins(): Collection
    {
        return $this->besoins;
    }

    public function addBesoin(Besoin $besoin): static
    {
        if (!$this->besoins->contains($besoin)) {
            $this->besoins->add($besoin);
        }

        return $this;
    }

    public function removeBesoin(Besoin $besoin): static
    {
        $this->besoins->removeElement($besoin);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBesoinbar(): ?Besoinbar
    {
        return $this->besoinbar;
    }

    public function setBesoinbar(?Besoinbar $besoinbar): static
    {
        $this->besoinbar = $besoinbar;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(?\DateTimeInterface $datecreation): static
    {
        $this->datecreation = $datecreation;

        return $this;
    }


    public function getDatecommencement(): ?\DateTimeInterface
    {
        return $this->datecommencement;
    }

    public function setDatecommencement(?\DateTimeInterface $datecommencement): static
    {
        $this->datecommencement = $datecommencement;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getReserveby(): ?User
    {
        return $this->reserveby;
    }

    public function setReserveby(?User $reserveby): static
    {
        $this->reserveby = $reserveby;

        return $this;
    }

}
