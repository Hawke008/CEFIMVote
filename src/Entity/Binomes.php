<?php

namespace App\Entity;

use App\Repository\BinomesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BinomesRepository::class)]
class Binomes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'binome', targetEntity: Votes::class)]
    private $votes;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $electeur_titulaire;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $electeur_suppleant;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $vote->setBinome($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getBinome() === $this) {
                $vote->setBinome(null);
            }
        }

        return $this;
    }

    public function getElecteurTitulaire(): ?string
    {
        return $this->electeur_titulaire;
    }

    public function setElecteurTitulaire(?string $electeur_titulaire): self
    {
        $this->electeur_titulaire = $electeur_titulaire;

        return $this;
    }

    public function getElecteurSuppleant(): ?string
    {
        return $this->electeur_suppleant;
    }

    public function setElecteurSuppleant(?string $electeur_suppleant): self
    {
        $this->electeur_suppleant = $electeur_suppleant;

        return $this;
    }
}
