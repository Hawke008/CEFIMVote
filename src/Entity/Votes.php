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

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $vote_blanc;

    #[ORM\ManyToOne(targetEntity: electeurs::class, inversedBy: 'votes')]
    private $electeur;

    #[ORM\ManyToOne(targetEntity: binomes::class, inversedBy: 'votes')]
    private $binome;

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

    public function isVoteBlanc(): ?bool
    {
        return $this->vote_blanc;
    }

    public function setVoteBlanc(?bool $vote_blanc): self
    {
        $this->vote_blanc = $vote_blanc;

        return $this;
    }

    public function getElecteur(): ?electeurs
    {
        return $this->electeur;
    }

    public function setElecteur(?electeurs $electeur): self
    {
        $this->electeur = $electeur;

        return $this;
    }

    public function getBinome(): ?binomes
    {
        return $this->binome;
    }

    public function setBinome(?binomes $binome): self
    {
        $this->binome = $binome;

        return $this;
    }
}
