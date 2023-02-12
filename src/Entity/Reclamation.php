<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $type_rec = null;

    #[ORM\Column(length: 20)]
    private ?string $titre_rec = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu_rec = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_rec = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $image_rec = null;

    #[ORM\OneToMany(mappedBy: 'reclamation', targetEntity: Reponse::class)]
    private Collection $liste_reponse;

    #[ORM\ManyToOne(inversedBy: 'liste_reclamations')]
    private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->liste_reponse = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeRec(): ?string
    {
        return $this->type_rec;
    }

    public function setTypeRec(string $type_rec): self
    {
        $this->type_rec = $type_rec;

        return $this;
    }

    public function getTitreRec(): ?string
    {
        return $this->titre_rec;
    }

    public function setTitreRec(string $titre_rec): self
    {
        $this->titre_rec = $titre_rec;

        return $this;
    }

    public function getContenuRec(): ?string
    {
        return $this->contenu_rec;
    }

    public function setContenuRec(string $contenu_rec): self
    {
        $this->contenu_rec = $contenu_rec;

        return $this;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->date_rec;
    }

    public function setDateRec(\DateTimeInterface $date_rec): self
    {
        $this->date_rec = $date_rec;

        return $this;
    }

    public function getImageRec(): ?string
    {
        return $this->image_rec;
    }

    public function setImageRec(?string $image_rec): self
    {
        $this->image_rec = $image_rec;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getListeReponse(): Collection
    {
        return $this->liste_reponse;
    }

    public function addListeReponse(Reponse $listeReponse): self
    {
        if (!$this->liste_reponse->contains($listeReponse)) {
            $this->liste_reponse->add($listeReponse);
            $listeReponse->setReclamation($this);
        }

        return $this;
    }

    public function removeListeReponse(Reponse $listeReponse): self
    {
        if ($this->liste_reponse->removeElement($listeReponse)) {
            // set the owning side to null (unless already changed)
            if ($listeReponse->getReclamation() === $this) {
                $listeReponse->setReclamation(null);
            }
        }

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
