<?php

namespace App\Entity;

use App\Repository\VotesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private $tour;

    #[ORM\ManyToOne(targetEntity: Electeurs::class, inversedBy: 'votes')]
    private $electeur;

    #[ORM\ManyToOne(targetEntity: Candidats::class, inversedBy: 'votes')]
    private $candidat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTour(): ?int
    {
        return $this->tour;
    }

    public function setTour(?int $tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    public function getElecteur(): ?Electeurs
    {
        return $this->electeur;
    }

    public function setElecteur(?Electeurs $electeur): self
    {
        $this->electeur = $electeur;

        return $this;
    }

    public function getCandidat(): ?candidats
    {
        return $this->candidat;
    }

    public function setCandidat(?candidats $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }
}
