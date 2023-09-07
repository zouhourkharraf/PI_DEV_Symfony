<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $nom_util = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prenom_util = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $pseudo_util = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $mot_de_passe_util = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $email_util = null;

    #[ORM\Column(nullable: true)]
    private ?int $age_util = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $genre_util = null;

    #[ORM\Column(length: 20)]
    private ?string $role_util = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Reclamation::class)]
    private Collection $liste_reclamations;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Reponse::class)]
    private Collection $liste_reponse;

    #[ORM\ManyToMany(targetEntity: Activite::class, inversedBy: 'liste_utilisateurs')]
    private Collection $liste_activites;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Cours::class)]
    private Collection $liste_cours;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Formation::class)]
    private Collection $liste_formations;

    public function __construct()
    {
        $this->liste_reclamations = new ArrayCollection();
        $this->liste_reponse = new ArrayCollection();
        $this->liste_activites = new ArrayCollection();
        $this->liste_cours = new ArrayCollection();
        $this->liste_formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtil(): ?string
    {
        return $this->nom_util;
    }

    public function setNomUtil(?string $nom_util): self
    {
        $this->nom_util = $nom_util;

        return $this;
    }

    public function getPrenomUtil(): ?string
    {
        return $this->prenom_util;
    }

    public function setPrenomUtil(?string $prenom_util): self
    {
        $this->prenom_util = $prenom_util;

        return $this;
    }

    public function getPseudoUtil(): ?string
    {
        return $this->pseudo_util;
    }

    public function setPseudoUtil(?string $pseudo_util): self
    {
        $this->pseudo_util = $pseudo_util;

        return $this;
    }

    public function getMotDePasseUtil(): ?string
    {
        return $this->mot_de_passe_util;
    }

    public function setMotDePasseUtil(?string $mot_de_passe_util): self
    {
        $this->mot_de_passe_util = $mot_de_passe_util;

        return $this;
    }

    public function getEmailUtil(): ?string
    {
        return $this->email_util;
    }

    public function setEmailUtil(?string $email_util): self
    {
        $this->email_util = $email_util;

        return $this;
    }

    public function getAgeUtil(): ?int
    {
        return $this->age_util;
    }

    public function setAgeUtil(?int $age_util): self
    {
        $this->age_util = $age_util;

        return $this;
    }

    public function getGenreUtil(): ?string
    {
        return $this->genre_util;
    }

    public function setGenreUtil(?string $genre_util): self
    {
        $this->genre_util = $genre_util;

        return $this;
    }

    public function getRoleUtil(): ?string
    {
        return $this->role_util;
    }

    public function setRoleUtil(string $role_util): self
    {
        $this->role_util = $role_util;

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getListeReclamations(): Collection
    {
        return $this->liste_reclamations;
    }

    public function addListeReclamation(Reclamation $listeReclamation): self
    {
        if (!$this->liste_reclamations->contains($listeReclamation)) {
            $this->liste_reclamations->add($listeReclamation);
            $listeReclamation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeListeReclamation(Reclamation $listeReclamation): self
    {
        if ($this->liste_reclamations->removeElement($listeReclamation)) {
            // set the owning side to null (unless already changed)
            if ($listeReclamation->getUtilisateur() === $this) {
                $listeReclamation->setUtilisateur(null);
            }
        }

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
            $listeReponse->setUtilisateur($this);
        }

        return $this;
    }

    public function removeListeReponse(Reponse $listeReponse): self
    {
        if ($this->liste_reponse->removeElement($listeReponse)) {
            // set the owning side to null (unless already changed)
            if ($listeReponse->getUtilisateur() === $this) {
                $listeReponse->setUtilisateur(null);
            }
        }

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
        }

        return $this;
    }

    public function removeListeActivite(Activite $listeActivite): self
    {
        $this->liste_activites->removeElement($listeActivite);

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
            $listeCour->setUtilisateur($this);
        }

        return $this;
    }

    public function removeListeCour(Cours $listeCour): self
    {
        if ($this->liste_cours->removeElement($listeCour)) {
            // set the owning side to null (unless already changed)
            if ($listeCour->getUtilisateur() === $this) {
                $listeCour->setUtilisateur(null);
            }
        }

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
            $listeFormation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeListeFormation(Formation $listeFormation): self
    {
        if ($this->liste_formations->removeElement($listeFormation)) {
            // set the owning side to null (unless already changed)
            if ($listeFormation->getUtilisateur() === $this) {
                $listeFormation->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
