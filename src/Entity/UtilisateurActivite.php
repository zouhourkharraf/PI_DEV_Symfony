<?php

namespace App\Entity;

use App\Repository\UtilisateurActiviteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurActiviteRepository::class)]
class UtilisateurActivite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur")
     */

    private $utilisateur;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Activite")
     */
    private $activite;

    public function getId(): ?int
    {
        return $this->id;
    }

    
}
