<?php

namespace App\Entity;

use App\Repository\CandidatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatsRepository::class)]
class Candidats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Sessions::class, inversedBy: 'candidats')]
    private $session;

    #[ORM\OneToOne(inversedBy: 'candidatsTitulaire', targetEntity: Electeurs::class, cascade: ['persist', 'remove'])]
    private $titulaire;

    #[ORM\OneToOne(inversedBy: 'candidatsSuppleant', targetEntity: Electeurs::class, cascade: ['persist', 'remove'])]
    private $suppleant;

    #[ORM\OneToMany(mappedBy: 'candidat', targetEntity: Votes::class)]
    private $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?Sessions
    {
        return $this->session;
    }

    public function setSession(?Sessions $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getTitulaire(): ?Electeurs
    {
        return $this->titulaire;
    }

    public function setTitulaire(?Electeurs $titulaire): self
    {
        $this->titulaire = $titulaire;

        return $this;
    }

    public function getSuppleant(): ?Electeurs
    {
        return $this->suppleant;
    }

    public function setSuppleant(?Electeurs $suppleant): self
    {
        $this->suppleant = $suppleant;

        return $this;
    }

    /**
     * @return Collection<int, Votes>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Votes $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setCandidat($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getCandidat() === $this) {
                $vote->setCandidat(null);
            }
        }

        return $this;
    }
}
