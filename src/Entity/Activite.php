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
    private ?string $nom_act = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_act = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_participants = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $position_act = null;

    #[ORM\ManyToOne(inversedBy: 'liste_activites')]
    private ?Type $type = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'liste_activites')]
    private Collection $liste_utilisateurs;

    public function __construct()
    {
        $this->liste_utilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAct(): ?string
    {
        return $this->nom_act;
    }

    public function setNomAct(string $nom_act): self
    {
        $this->nom_act = $nom_act;

        return $this;
    }

    public function getDateAct(): ?\DateTimeInterface
    {
        return $this->date_act;
    }

    public function setDateAct(?\DateTimeInterface $date_act): self
    {
        $this->date_act = $date_act;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nb_participants;
    }

    public function setNbParticipants(?int $nb_participants): self
    {
        $this->nb_participants = $nb_participants;

        return $this;
    }

    public function getPositionAct(): ?string
    {
        return $this->position_act;
    }

    public function setPositionAct(?string $position_act): self
    {
        $this->position_act = $position_act;

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
        return $this->liste_utilisateurs;
    }

    public function addListeUtilisateur(Utilisateur $listeUtilisateur): self
    {
        if (!$this->liste_utilisateurs->contains($listeUtilisateur)) {
            $this->liste_utilisateurs->add($listeUtilisateur);
            $listeUtilisateur->addListeActivite($this);
        }

        return $this;
    }

    public function removeListeUtilisateur(Utilisateur $listeUtilisateur): self
    {
        if ($this->liste_utilisateurs->removeElement($listeUtilisateur)) {
            $listeUtilisateur->removeListeActivite($this);
        }

        return $this;
    }
}
