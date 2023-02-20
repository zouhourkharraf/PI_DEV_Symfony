<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Nom de l'activité est obligatoire")]
    private ?string $nomact = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    
    private ?\DateTimeInterface $dateact = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"nbparticipants est obligatoire")]
    #[Assert\Positive(message:'Le nombre de participants doit être un entier positif ou nul.')]
    private ?int $nbparticipants = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank(message:"positionact est obligatoire")]
    private ?string $positionact = null;

  
    #[ORM\ManyToOne(inversedBy: 'liste_activites')]
    #[Assert\NotBlank(message:"typeact est obligatoire")]
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
        return $this->liste_utilisateurs;
    }

    public function addListeUtilisateur(Utilisateur $liste_Utilisateur): self
    {
        if (!$this->liste_utilisateurs->contains($liste_Utilisateur)) {
            $this->liste_utilisateurs->add($liste_Utilisateur);
            $liste_Utilisateur->addListeActivite($this);
        }

        return $this;
    }

    public function removeListeUtilisateur(Utilisateur $liste_Utilisateur): self
    {
        if ($this->liste_utilisateurs->removeElement($liste_Utilisateur)) {
            $liste_Utilisateur->removeListeActivite($this);
        }

        return $this;
    }

/*
    public function getTypeName()
    {
        return $this->getType()->getNomType();
    }
    */

    public function __toString()
    {
        return $this->nomact;
    
    }

  
}
