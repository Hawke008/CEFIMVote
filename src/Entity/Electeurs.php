<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ElecteursRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ElecteursRepository::class)]
class Electeurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['electeurs'])]
    private $id;

    #[Groups(['electeurs'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $nom;
    
    #[Groups(['electeurs'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[ORM\Column(type: 'blob')]
    private $signature;

    #[ORM\ManyToOne(targetEntity: Sessions::class, inversedBy: 'electeurs')]
    #[ORM\JoinColumn(nullable: true)]
    private $session;

    #[ORM\OneToOne(mappedBy: 'titulaire', targetEntity: Candidats::class, cascade: ['persist', 'remove'])]
    private $candidatsTitulaire;

    #[ORM\OneToOne(mappedBy: 'suppleant', targetEntity: Candidats::class, cascade: ['persist', 'remove'])]
    private $candidatsSuppleant;

    #[ORM\OneToMany(mappedBy: 'electeur', targetEntity: Votes::class)]
    private $votes;


    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setSignature($signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getSession(): ?sessions
    {
        return $this->session;
    }

    public function setSession(?sessions $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getCandidatsTitulaire(): ?Candidats
    {
        return $this->candidatsTitulaire;
    }

    public function setCandidatsTitulaire(?Candidats $candidatsTitulaire): self
    {
        // unset the owning side of the relation if necessary
        if ($candidatsTitulaire === null && $this->candidatsTitulaire !== null) {
            $this->candidatsTitulaire->setTitulaire(null);
        }

        // set the owning side of the relation if necessary
        if ($candidatsTitulaire !== null && $candidatsTitulaire->getTitulaire() !== $this) {
            $candidatsTitulaire->setTitulaire($this);
        }

        $this->candidatsTitulaire = $candidatsTitulaire;

        return $this;
    }

    public function getCandidatsSuppleant(): ?Candidats
    {
        return $this->candidatsSuppleant;
    }

    public function setCandidatsSuppleant(?Candidats $candidatsSuppleant): self
    {
        // unset the owning side of the relation if necessary
        if ($candidatsSuppleant === null && $this->candidatsSuppleant !== null) {
            $this->candidatsSuppleant->setSuppleant(null);
        }

        // set the owning side of the relation if necessary
        if ($candidatsSuppleant !== null && $candidatsSuppleant->getSuppleant() !== $this) {
            $candidatsSuppleant->setSuppleant($this);
        }

        $this->candidatsSuppleant = $candidatsSuppleant;

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
            $vote->setElecteur($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getElecteur() === $this) {
                $vote->setElecteur(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->prenom." ".$this->nom;
    }
}
