<?php

namespace App\Entity;

use App\Repository\SessionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionsRepository::class)]
class Sessions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $heure_debut;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $heure_fin;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $promotion;

    #[ORM\Column(type: 'datetime')]
    private $date_debut_promo;

    #[ORM\Column(type: 'datetime')]
    private $date_fin_promo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ville;

    #[ORM\ManyToOne(targetEntity: Responsables::class, inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: true)]
    private $responsable;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Electeurs::class, orphanRemoval: true)]
    private $electeurs;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Candidats::class)]
    private $candidats;

    #[ORM\Column(type: 'smallint')]
    private $state;

    #[ORM\Column(type: 'string', length: 8)]
    private $codeSession;

    public function __construct()
    {
        $this->electeurs = new ArrayCollection();
        $this->candidats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebut(): ?\DateTime
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTime $heure_debut): self
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?\DateTime
    {
        return $this->heure_fin;
    }

    public function setHeureFin(?\DateTime $heure_fin): self
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

    public function getPromotion(): ?string
    {
        return $this->promotion;
    }

    public function setPromotion(string $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getDateDebutPromo(): ?\DateTime
    {
        return $this->date_debut_promo;
    }

    public function setDateDebutPromo(\DateTime $date_debut_promo): self
    {
        $this->date_debut_promo = $date_debut_promo;

        return $this;
    }

    public function getDateFinPromo(): ?\DateTime
    {
        return $this->date_fin_promo;
    }

    public function setDateFinPromo(\DateTime $date_fin_promo): self
    {
        $this->date_fin_promo = $date_fin_promo;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getResponsable(): ?responsables
    {
        return $this->responsable;
    }

    public function setResponsable(?responsables $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @return Collection<int, Electeurs>
     */
    public function getElecteurs(): Collection
    {
        return $this->electeurs;
    }

    public function addElecteur(Electeurs $electeur): self
    {
        if (!$this->electeurs->contains($electeur)) {
            $this->electeurs[] = $electeur;
            $electeur->setSession($this);
        }

        return $this;
    }

    public function removeElecteur(Electeurs $electeur): self
    {
        if ($this->electeurs->removeElement($electeur)) {
            // set the owning side to null (unless already changed)
            if ($electeur->getSession() === $this) {
                $electeur->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Candidats>
     */
    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function addCandidat(Candidats $candidat): self
    {
        if (!$this->candidats->contains($candidat)) {
            $this->candidats[] = $candidat;
            $candidat->setSession($this);
        }

        return $this;
    }

    public function removeCandidat(Candidats $candidat): self
    {
        if ($this->candidats->removeElement($candidat)) {
            // set the owning side to null (unless already changed)
            if ($candidat->getSession() === $this) {
                $candidat->setSession(null);
            }
        }

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCodeSession(): ?string
    {
        return $this->codeSession;
    }

    public function setCodeSession(string $codeSession): self
    {
        $this->codeSession = $codeSession;

        return $this;
    }

    public function __toString()
    {
        return $this->promotion;
    }
}
