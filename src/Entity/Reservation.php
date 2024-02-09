<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    public function __construct()
    {
        $this->salles = new ArrayCollection();
        $this->objectifs = new ArrayCollection();
        $this->besoins = new ArrayCollection();
        $this->datecreation=new \DateTime();
        $this->cloture=false;

        $this->calendrier=false;
        $this->commfaite=false;
        $this->comm=false;
        $this->commannulee=false;
        $this->recurrent=false;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type : 'string', length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type : 'string', length: 255, nullable: true)]
    private ?string $personnepresente = null;

    #[ORM\Column(type: 'integer')]
    private ?int $nbpersonne = null;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\User $refbase = null;


    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(nullable: false)]
    private ?\App\Entity\User $reserveby = null;

    #[ORM\ManyToOne(targetEntity: 'User')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\User $priseenmainby = null;

    #[ORM\ManyToOne(targetEntity: 'Etape')]
    #[ORM\JoinColumn(nullable: false)]
    private ?\App\Entity\Etape $etape = null;


    #[ORM\ManyToOne(targetEntity: 'Collectif')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Collectif $collectif = null;


    #[ORM\ManyToOne(targetEntity: 'Typereservation')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Typereservation $typereservation = null;

    #[ORM\ManyToOne(targetEntity: 'Besoinbar')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Besoinbar $besoinbar = null;

    #[ORM\ManyToOne(targetEntity: 'Statutevent')]
    #[ORM\JoinColumn(nullable: true)]
    private ?\App\Entity\Statutevent $statutevent = null;

    #[ORM\ManyToOne(targetEntity: 'Mailreservation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?\App\Entity\Mailreservation $mailreservation = null;

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
    private ?bool $commfaite = null;

    #[ORM\Column (type: 'boolean', nullable: false)]
    private ?bool $commannulee = null;

    #[ORM\Column (type: 'boolean', nullable: false)]
    private ?bool $cloture = null;

    #[ORM\Column (type: 'boolean', nullable: false)]
    private ?bool $recurrent = null;

    #[ORM\Column (type: 'boolean', nullable: false)]
    private ?bool $calendrier = null;

    #[ORM\Column (type: 'boolean', nullable: false)]
    private ?bool $comm = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeInterface $datedebut = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeInterface $datefin = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $datecreation = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $autredate = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $formulation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $autreinfo = null;


    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $doc1 = null;

    #[Assert\File(maxSize: '3048k')]
    #[Vich\UploadableField(mapping: 'doc1_directory', fileNameProperty: 'doc1', size:'doc1Size')]
    private ?File $doc1File = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $doc1Size = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTimeImmutable|\DateTimeInterface|null $doc1updatedAt = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $doc2 = null;

    /**
     *
     * @Vich\UploadableField(mapping="doc2_directory", fileNameProperty="doc2", size="doc2Size")
     *
     */
    #[Assert\File(maxSize: '3048k')]
    #[Vich\UploadableField(mapping: 'doc2_directory', fileNameProperty: 'doc2', size:'doc2Size')]
    private ?File $doc2File = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $doc2Size = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTimeImmutable|\DateTimeInterface|null $doc2updatedAt = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $doc3 = null;

    /**
     *
     * @Vich\UploadableField(mapping="doc3_directory", fileNameProperty="doc3", size="doc3Size")
     *
     */
    #[Assert\File(maxSize: '3048k')]
    #[Vich\UploadableField(mapping: 'doc3_directory', fileNameProperty: 'doc3', size:'doc3Size')]

    private ?File $doc3File = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $doc3Size = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private \DateTimeImmutable|\DateTimeInterface|null $doc3updatedAt = null;





    public function getId(): ?int
    {
        return $this->id;
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

    public function isGratuit(): ?bool
    {
        return $this->gratuit;
    }

    public function setGratuit(bool $gratuit): static
    {
        $this->gratuit = $gratuit;

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

    public function getTypereservation(): ?Typereservation
    {
        return $this->typereservation;
    }

    public function setTypereservation(?Typereservation $typereservation): static
    {
        $this->typereservation = $typereservation;

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getFormulation(): ?string
    {
        return $this->formulation;
    }

    public function setFormulation(?string $formulation): static
    {
        $this->formulation = $formulation;

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

    public function getStatutevent(): ?Statutevent
    {
        return $this->statutevent;
    }

    public function setStatutevent(?Statutevent $statutevent): static
    {
        $this->statutevent = $statutevent;

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

    public function getAutreinfo(): ?string
    {
        return $this->autreinfo;
    }

    public function setAutreinfo(?string $autreinfo): static
    {
        $this->autreinfo = $autreinfo;

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

    public function getReserveby(): ?User
    {
        return $this->reserveby;
    }

    public function setReserveby(?User $reserveby): static
    {
        $this->reserveby = $reserveby;

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

    public function getNomcollectif(): ?string
    {
        return $this->nomcollectif;
    }

    public function setNomcollectif(string $nomcollectif): static
    {
        $this->nomcollectif = $nomcollectif;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }


    public function getAutredate(): ?\DateTimeInterface
    {
        return $this->autredate;
    }

    public function setAutredate(?\DateTimeInterface $autredate): static
    {
        $this->autredate = $autredate;

        return $this;
    }

    public function getAutreheure(): ?\DateTimeInterface
    {
        return $this->autreheure;
    }

    public function setAutreheure(?\DateTimeInterface $autreheure): static
    {
        $this->autreheure = $autreheure;

        return $this;
    }

    public function isComm(): ?bool
    {
        return $this->comm;
    }

    public function setComm(?bool $comm): static
    {
        $this->comm = $comm;

        return $this;
    }


    public function setDoc1File(File $doc = null)
    {
        $this->doc1File = $doc;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($doc) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->doc1updatedAt = new \DateTimeImmutable();
        }
    }

    public function getDoc1File(): ?File
    {
        return $this->doc1File;
    }

    public function getDoc1(): ?string
    {
        return $this->doc1;
    }

    public function setDoc1(?string $doc1): self
    {
        $this->doc1 = $doc1;

        return $this;
    }

    public function getDoc1Size(): ?int
    {
        return $this->doc1Size;
    }

    public function setDoc1Size(?int $doc1Size): self
    {
        $this->doc1Size = $doc1Size;

        return $this;
    }

    public function getDoc1updatedAt(): ?\DateTimeInterface
    {
        return $this->doc1updatedAt;
    }

    public function setDoc1updatedAt(?\DateTimeInterface $doc1updatedAt): self
    {
        $this->doc1updatedAt = $doc1updatedAt;

        return $this;
    }

    public function setDoc2File(File $doc = null)
    {
        $this->doc2File = $doc;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($doc) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->doc2updatedAt = new \DateTimeImmutable();
        }
    }

    public function getDoc2File(): ?File
    {
        return $this->doc2File;
    }

    public function getDoc2(): ?string
    {
        return $this->doc1;
    }

    public function setDoc2(?string $doc2): self
    {
        $this->doc2 = $doc2;

        return $this;
    }

    public function getDoc2Size(): ?int
    {
        return $this->doc2Size;
    }

    public function setDoc2Size(?int $doc2Size): self
    {
        $this->doc2Size = $doc2Size;

        return $this;
    }

    public function getDoc2updatedAt(): ?\DateTimeInterface
    {
        return $this->doc2updatedAt;
    }

    public function setDoc2updatedAt(?\DateTimeInterface $doc2updatedAt): self
    {
        $this->doc2updatedAt = $doc2updatedAt;

        return $this;
    }

    public function setDoc3File(File $doc = null)
    {
        $this->doc3File = $doc;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($doc) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->doc3updatedAt = new \DateTimeImmutable();
        }
    }

    public function getDoc3File(): ?File
    {
        return $this->doc2File;
    }

    public function getDoc3(): ?string
    {
        return $this->doc1;
    }

    public function setDoc3(?string $doc3): self
    {
        $this->doc3 = $doc3;

        return $this;
    }

    public function getDoc3Size(): ?int
    {
        return $this->doc3Size;
    }

    public function setDoc3Size(?int $doc3Size): self
    {
        $this->doc3Size = $doc3Size;

        return $this;
    }

    public function getDoc3updatedAt(): ?\DateTimeInterface
    {
        return $this->doc3updatedAt;
    }

    public function setDoc3updatedAt(?\DateTimeInterface $doc3updatedAt): self
    {
        $this->doc3updatedAt = $doc3updatedAt;

        return $this;
    }

    public function getPriseenmainby(): ?User
    {
        return $this->priseenmainby;
    }

    public function setPriseenmainby(?User $priseenmainby): static
    {
        $this->priseenmainby = $priseenmainby;

        return $this;
    }

    public function getEtape(): ?Etape
    {
        return $this->etape;
    }

    public function setEtape(?Etape $etape): static
    {
        $this->etape = $etape;

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

    public function isCloture(): ?bool
    {
        return $this->cloture;
    }

    public function setCloture(?bool $cloture): static
    {
        $this->cloture = $cloture;

        return $this;
    }

    public function isPasse(): ?bool
    {
        return $this->passe;
    }

    public function setPasse(?bool $passe): static
    {
        $this->passe = $passe;

        return $this;
    }


    public function isCalendrier(): ?bool
    {
        return $this->calendrier;
    }

    public function setCalendrier(?bool $calendrier): static
    {
        $this->calendrier = $calendrier;

        return $this;
    }

    public function isCommfaite(): ?bool
    {
        return $this->commfaite;
    }

    public function setCommfaite(?bool $commfaite): static
    {
        $this->commfaite = $commfaite;

        return $this;
    }

    public function isCommannulee(): ?bool
    {
        return $this->commannulee;
    }

    public function setCommannulee(?bool $commannulee): static
    {
        $this->commannulee = $commannulee;

        return $this;
    }

    public function isRecurrent(): ?bool
    {
        return $this->recurrent;
    }

    public function setRecurrent(bool $recurrent): static
    {
        $this->recurrent = $recurrent;

        return $this;
    }
}
