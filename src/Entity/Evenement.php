<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom_ev = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dated_ev = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datef_ev = null;

    #[ORM\Column(length: 50)]
    private ?string $lieu_ev = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $desc_ev = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $image_ev = null;

    #[ORM\OneToMany(mappedBy: 'evenement', targetEntity: Don::class)]
    private Collection $liste_dons;

    public function __construct()
    {
        $this->liste_dons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEv(): ?string
    {
        return $this->nom_ev;
    }

    public function setNomEv(string $nom_ev): self
    {
        $this->nom_ev = $nom_ev;

        return $this;
    }

    public function getDatedEv(): ?\DateTimeInterface
    {
        return $this->dated_ev;
    }

    public function setDatedEv(\DateTimeInterface $dated_ev): self
    {
        $this->dated_ev = $dated_ev;

        return $this;
    }

    public function getDatefEv(): ?\DateTimeInterface
    {
        return $this->datef_ev;
    }

    public function setDatefEv(\DateTimeInterface $datef_ev): self
    {
        $this->datef_ev = $datef_ev;

        return $this;
    }

    public function getLieuEv(): ?string
    {
        return $this->lieu_ev;
    }

    public function setLieuEv(string $lieu_ev): self
    {
        $this->lieu_ev = $lieu_ev;

        return $this;
    }

    public function getDescEv(): ?string
    {
        return $this->desc_ev;
    }

    public function setDescEv(?string $desc_ev): self
    {
        $this->desc_ev = $desc_ev;

        return $this;
    }

    public function getImageEv(): ?string
    {
        return $this->image_ev;
    }

    public function setImageEv(?string $image_ev): self
    {
        $this->image_ev = $image_ev;

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getListeDons(): Collection
    {
        return $this->liste_dons;
    }

    public function addListeDon(Don $listeDon): self
    {
        if (!$this->liste_dons->contains($listeDon)) {
            $this->liste_dons->add($listeDon);
            $listeDon->setEvenement($this);
        }

        return $this;
    }

    public function removeListeDon(Don $listeDon): self
    {
        if ($this->liste_dons->removeElement($listeDon)) {
            // set the owning side to null (unless already changed)
            if ($listeDon->getEvenement() === $this) {
                $listeDon->setEvenement(null);
            }
        }

        return $this;
    }
}
