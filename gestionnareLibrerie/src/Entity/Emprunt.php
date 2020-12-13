<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmpruntRepository::class)
 */
class Emprunt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Livre;

    /**
     * @ORM\ManyToOne(targetEntity=Abonne::class, inversedBy="emprunts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Abonne;

    /**
     * @ORM\Column(type="date")
     */
    private $date_emprunt;

    /**
     * @ORM\Column(type="date")
     */
    private $date_retour;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLivre(): ?Livre
    {
        return $this->Livre;
    }

    public function setLivre(?Livre $Livre): self
    {
        $this->Livre = $Livre;

        return $this;
    }

    public function getAbonne(): ?Abonne
    {
        return $this->Abonne;
    }

    public function setAbonne(?Abonne $Abonne): self
    {
        $this->Abonne = $Abonne;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->date_emprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $date_emprunt): self
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->date_retour;
    }

    public function setDateRetour(\DateTimeInterface $date_retour): self
    {
        $this->date_retour = $date_retour;

        return $this;
    }
}
