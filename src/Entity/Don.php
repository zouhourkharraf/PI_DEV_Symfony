<?php

namespace App\Entity;

use App\Repository\DonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonRepository::class)]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type_don = null;

    #[ORM\Column(nullable: true)]
    private ?int $somme_don = null;

    #[ORM\ManyToOne(inversedBy: 'liste_dons')]
    private ?Evenement $evenement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDon(): ?string
    {
        return $this->type_don;
    }

    public function setTypeDon(string $type_don): self
    {
        $this->type_don = $type_don;

        return $this;
    }

    public function getSommeDon(): ?int
    {
        return $this->somme_don;
    }

    public function setSommeDon(?int $somme_don): self
    {
        $this->somme_don = $somme_don;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }
}
