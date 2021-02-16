<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nomCampus;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="campus")
     */
    private $etudiants;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="campus")
     */
    private $sorties;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCampus(): ?string
    {
        return $this->nomCampus;
    }

    public function setNomCampus(string $nomCampus): self
    {
        $this->nomCampus = $nomCampus;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(User $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setCampus($this);
        }

        return $this;
    }

    public function removeEtudiant(User $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getCampus() === $this) {
                $etudiant->setCampus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setYes($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getYes() === $this) {
                $sorty->setYes(null);
            }
        }

        return $this;
    }
}
