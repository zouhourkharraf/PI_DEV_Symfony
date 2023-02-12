<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_form = null;

    #[ORM\Column(nullable: true)]
    private ?int $duree_form = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_form = null;

    #[ORM\ManyToOne(inversedBy: 'liste_formations')]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'liste_formations')]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomForm(): ?string
    {
        return $this->nom_form;
    }

    public function setNomForm(string $nom_form): self
    {
        $this->nom_form = $nom_form;

        return $this;
    }

    public function getDureeForm(): ?int
    {
        return $this->duree_form;
    }

    public function setDureeForm(?int $duree_form): self
    {
        $this->duree_form = $duree_form;

        return $this;
    }

    public function getDescriptionForm(): ?string
    {
        return $this->description_form;
    }

    public function setDescriptionForm(?string $description_form): self
    {
        $this->description_form = $description_form;

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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
