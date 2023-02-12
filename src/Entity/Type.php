<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $nom_type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_type = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Activite::class)]
    private Collection $liste_activites;

    public function __construct()
    {
        $this->liste_activites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomType(): ?string
    {
        return $this->nom_type;
    }

    public function setNomType(?string $nom_type): self
    {
        $this->nom_type = $nom_type;

        return $this;
    }

    public function getDescriptionType(): ?string
    {
        return $this->description_type;
    }

    public function setDescriptionType(?string $description_type): self
    {
        $this->description_type = $description_type;

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getListeActivites(): Collection
    {
        return $this->liste_activites;
    }

    public function addListeActivite(Activite $listeActivite): self
    {
        if (!$this->liste_activites->contains($listeActivite)) {
            $this->liste_activites->add($listeActivite);
            $listeActivite->setType($this);
        }

        return $this;
    }

    public function removeListeActivite(Activite $listeActivite): self
    {
        if ($this->liste_activites->removeElement($listeActivite)) {
            // set the owning side to null (unless already changed)
            if ($listeActivite->getType() === $this) {
                $listeActivite->setType(null);
            }
        }

        return $this;
    }
}
