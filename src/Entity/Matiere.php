<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom_mat = null;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Cours::class)]
    private Collection $liste_cours;

    public function __construct()
    {
        $this->liste_cours = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMat(): ?string
    {
        return $this->nom_mat;
    }

    public function setNomMat(string $nom_mat): self
    {
        $this->nom_mat = $nom_mat;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getListeCours(): Collection
    {
        return $this->liste_cours;
    }

    public function addListeCour(Cours $listeCour): self
    {
        if (!$this->liste_cours->contains($listeCour)) {
            $this->liste_cours->add($listeCour);
            $listeCour->setMatiere($this);
        }

        return $this;
    }

    public function removeListeCour(Cours $listeCour): self
    {
        if ($this->liste_cours->removeElement($listeCour)) {
            // set the owning side to null (unless already changed)
            if ($listeCour->getMatiere() === $this) {
                $listeCour->setMatiere(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->id;
    }
}
