<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nomUtil;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $prenomUtil;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $pseudoUtil;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $motDePasseUtil;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $emailUtil;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ageUtil;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $genreUtil;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $roleUtil;

    /**
     * @ORM\ManyToMany(targetEntity=Formation::class, mappedBy="utilisateur")
     */
    private $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtil(): ?string
    {
        return $this->nomUtil;
    }

    public function setNomUtil(?string $nomUtil): self
    {
        $this->nomUtil = $nomUtil;

        return $this;
    }

    public function getPrenomUtil(): ?string
    {
        return $this->prenomUtil;
    }

    public function setPrenomUtil(?string $prenomUtil): self
    {
        $this->prenomUtil = $prenomUtil;

        return $this;
    }

    public function getPseudoUtil(): ?string
    {
        return $this->pseudoUtil;
    }

    public function setPseudoUtil(?string $pseudoUtil): self
    {
        $this->pseudoUtil = $pseudoUtil;

        return $this;
    }

    public function getMotDePasseUtil(): ?string
    {
        return $this->motDePasseUtil;
    }

    public function setMotDePasseUtil(?string $motDePasseUtil): self
    {
        $this->motDePasseUtil = $motDePasseUtil;

        return $this;
    }

    public function getEmailUtil(): ?string
    {
        return $this->emailUtil;
    }

    public function setEmailUtil(?string $emailUtil): self
    {
        $this->emailUtil = $emailUtil;

        return $this;
    }

    public function getAgeUtil(): ?int
    {
        return $this->ageUtil;
    }

    public function setAgeUtil(?int $ageUtil): self
    {
        $this->ageUtil = $ageUtil;

        return $this;
    }

    public function getGenreUtil(): ?string
    {
        return $this->genreUtil;
    }

    public function setGenreUtil(?string $genreUtil): self
    {
        $this->genreUtil = $genreUtil;

        return $this;
    }

    public function getRoleUtil(): ?string
    {
        return $this->roleUtil;
    }

    public function setRoleUtil(string $roleUtil): self
    {
        $this->roleUtil = $roleUtil;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->addUtilisateur($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeUtilisateur($this);
        }

        return $this;
    }
}
