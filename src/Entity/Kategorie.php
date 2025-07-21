<?php

namespace App\Entity;

use App\Repository\KategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Gericht;

#[ORM\Entity(repositoryClass: KategorieRepository::class)]
class Kategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: "kategorie", targetEntity: Gericht::class)]
    private Collection $gerichte;

    public function __construct()
    {
        $this->gerichte = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Gericht>
     */
    public function getGerichte(): Collection
    {
        return $this->gerichte;
    }

    public function addGericht(Gericht $gericht): static
    {
        if (!$this->gerichte->contains($gericht)) {
            $this->gerichte->add($gericht);
            $gericht->setKategorie($this); // update owning side
        }

        return $this;
    }

    public function removeGericht(Gericht $gericht): static
    {
        if ($this->gerichte->removeElement($gericht)) {
            // set the owning side to null (unless already changed)
            if ($gericht->getKategorie() === $this) {
                $gericht->setKategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
