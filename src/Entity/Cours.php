<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_cour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $temps_cour = null;

    #[ORM\Column(length: 30)]
    private ?string $titre_cour = null;



    #[ORM\ManyToOne(inversedBy: 'liste_cours')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'liste_cours')]
    private ?Matiere $matiere = null;

    #[ORM\Column(length: 255)]
    private ?string $fichier = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCour(): ?\DateTimeInterface
    {
        return $this->date_cour;
    }

    public function setDateCour(\DateTimeInterface $date_cour): self
    {
        $this->date_cour = $date_cour;

        return $this;
    }

    public function getTempsCour(): ?\DateTimeInterface
    {
        return $this->temps_cour;
    }

    public function setTempsCour(\DateTimeInterface $temps_cour): self
    {
        $this->temps_cour = $temps_cour;

        return $this;
    }

    public function getTitreCour(): ?string
    {
        return $this->titre_cour;
    }

    public function setTitreCour(string $titre_cour): self
    {
        $this->titre_cour = $titre_cour;

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

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }
    
}
