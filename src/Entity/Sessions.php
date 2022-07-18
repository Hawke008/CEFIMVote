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

    #[ORM\Column(type: 'datetime_immutable')]
    private $heure_debut;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $heure_fin;

    #[ORM\Column(type: 'string', length: 255)]
    private $promotion;

    #[ORM\Column(type: 'datetime_immutable')]
    private $date_debut_promo;

    #[ORM\Column(type: 'datetime_immutable')]
    private $date_fin_promo;

    #[ORM\Column(type: 'string', length: 255)]
    private $ville;

    #[ORM\Column(type: 'blob', nullable: true)]
    private $pdf;

    #[ORM\ManyToOne(targetEntity: responsables::class, inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private $responsable;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Electeurs::class, orphanRemoval: true)]
    private $electeurs;

    public function __construct()
    {
        $this->electeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDebut(): ?\DateTimeImmutable
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTimeImmutable $heure_debut): self
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeImmutable
    {
        return $this->heure_fin;
    }

    public function setHeureFin(?\DateTimeImmutable $heure_fin): self
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

    public function getDateDebutPromo(): ?\DateTimeImmutable
    {
        return $this->date_debut_promo;
    }

    public function setDateDebutPromo(\DateTimeImmutable $date_debut_promo): self
    {
        $this->date_debut_promo = $date_debut_promo;

        return $this;
    }

    public function getDateFinPromo(): ?\DateTimeImmutable
    {
        return $this->date_fin_promo;
    }

    public function setDateFinPromo(\DateTimeImmutable $date_fin_promo): self
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

    public function getPdf()
    {
        return $this->pdf;
    }

    public function setPdf($pdf): self
    {
        $this->pdf = $pdf;

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
}
