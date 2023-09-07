<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_catg = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Formation::class)]
    private Collection $liste_formations;

    public function __construct()
    {
        $this->liste_formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCatg(): ?string
    {
        return $this->nom_catg;
    }

    public function setNomCatg(string $nom_catg): self
    {
        $this->nom_catg = $nom_catg;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getListeFormations(): Collection
    {
        return $this->liste_formations;
    }

    public function addListeFormation(Formation $listeFormation): self
    {
        if (!$this->liste_formations->contains($listeFormation)) {
            $this->liste_formations->add($listeFormation);
            $listeFormation->setCategorie($this);
        }

        return $this;
    }

    public function removeListeFormation(Formation $listeFormation): self
    {
        if ($this->liste_formations->removeElement($listeFormation)) {
            // set the owning side to null (unless already changed)
            if ($listeFormation->getCategorie() === $this) {
                $listeFormation->setCategorie(null);
            }
        }

        return $this;
    }
    //  ************************ To String ************************************
    public function __toString()
    {
        return $this->nom_catg;
    }
}
