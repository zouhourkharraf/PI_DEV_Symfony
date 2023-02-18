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
    private ?string $nomtype = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptiontype = null;
   


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
        return $this->nomtype;
    }

    public function setNomType(?string $nomtype): self
    {
        $this->nomtype = $nomtype;

        return $this;
    }

    public function getDescriptionType(): ?string
    {
        return $this->descriptiontype;
    }

    public function setDescriptionType(?string $descriptiontype): self
    {
        $this->descriptiontype = $descriptiontype;

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


    public function __toString()
    {
        return $this->nomtype;
        return $this->descriptiontype;
        
    }


    
}
