<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nomact = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateact = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbparticipants = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $positionact = null;

    #[ORM\ManyToOne(inversedBy: 'listeactivites')]
    private ?Type $type = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'listeactivites')]
    private Collection $listeutilisateurs;

    public function __construct()
    {
        $this->listeutilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAct(): ?string
    {
        return $this->nomact;
    }

    public function setNomAct(string $nomact): self
    {
        $this->nomact = $nomact;

        return $this;
    }

    public function getDateAct(): ?\DateTimeInterface
    {
        return $this->dateact;
    }

    public function setDateAct(?\DateTimeInterface $dateact): self
    {
        $this->dateact = $dateact;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbparticipants;
    }

    public function setNbParticipants(?int $nbparticipants): self
    {
        $this->nbparticipants = $nbparticipants;

        return $this;
    }

    public function getPositionAct(): ?string
    {
        return $this->positionact;
    }

    public function setPositionAct(?string $positionact): self
    {
        $this->positionact = $positionact;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getListeUtilisateurs(): Collection
    {
        return $this->listeutilisateurs;
    }

    public function addListeUtilisateur(Utilisateur $listeUtilisateur): self
    {
        if (!$this->listeutilisateurs->contains($listeUtilisateur)) {
            $this->listeutilisateurs->add($listeUtilisateur);
            $listeUtilisateur->addListeActivite($this);
        }

        return $this;
    }

    public function removeListeUtilisateur(Utilisateur $listeUtilisateur): self
    {
        if ($this->listeutilisateurs->removeElement($listeUtilisateur)) {
            $listeUtilisateur->removeListeActivite($this);
        }

        return $this;
    }
}
