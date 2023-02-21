<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nomForm;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dureeForm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptionForm;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="formations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToMany(targetEntity=Utilisateur::class, inversedBy="formations")
     */
    private $utilisateur;

    public function __construct()
    {
        $this->utilisateur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomForm(): ?string
    {
        return $this->nomForm;
    }

    public function setNomForm(?string $nomForm): self
    {
        $this->nomForm = $nomForm;

        return $this;
    }

    public function getDureeForm(): ?int
    {
        return $this->dureeForm;
    }

    public function setDureeForm(?int $dureeForm): self
    {
        $this->dureeForm = $dureeForm;

        return $this;
    }

    public function getDescriptionForm(): ?string
    {
        return $this->descriptionForm;
    }

    public function setDescriptionForm(?string $descriptionForm): self
    {
        $this->descriptionForm = $descriptionForm;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateur(): Collection
    {
        return $this->utilisateur;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateur->contains($utilisateur)) {
            $this->utilisateur[] = $utilisateur;
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur->removeElement($utilisateur);

        return $this;
    }
}
