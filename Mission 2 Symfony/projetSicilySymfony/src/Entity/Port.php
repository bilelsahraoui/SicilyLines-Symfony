<?php

namespace App\Entity;

use App\Repository\PortRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PortRepository::class)
 */
class Port
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Liaison::class, mappedBy="portDepart")
     */
    private $liaisons1;

    /**
     * @ORM\OneToMany(targetEntity=Liaison::class, mappedBy="portArrivee")
     */
    private $liaisons2;

    public function __construct()
    {
        $this->liaisons1 = new ArrayCollection();
        $this->liaisons2 = new ArrayCollection();
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

    /**
     * @return Collection<int, Liaison>
     */
    public function getLiaisons1(): Collection
    {
        return $this->liaisons1;
    }

    public function addLiaisons1(Liaison $liaisons1): self
    {
        if (!$this->liaisons1->contains($liaisons1)) {
            $this->liaisons1[] = $liaisons1;
            $liaisons1->setPortDepart($this);
        }

        return $this;
    }

    public function removeLiaisons1(Liaison $liaisons1): self
    {
        if ($this->liaisons1->removeElement($liaisons1)) {
            // set the owning side to null (unless already changed)
            if ($liaisons1->getPortDepart() === $this) {
                $liaisons1->setPortDepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Liaison>
     */
    public function getLiaisons2(): Collection
    {
        return $this->liaisons2;
    }

    public function addLiaisons2(Liaison $liaisons2): self
    {
        if (!$this->liaisons2->contains($liaisons2)) {
            $this->liaisons2[] = $liaisons2;
            $liaisons2->setPortArrivee($this);
        }

        return $this;
    }

    public function removeLiaisons2(Liaison $liaisons2): self
    {
        if ($this->liaisons2->removeElement($liaisons2)) {
            // set the owning side to null (unless already changed)
            if ($liaisons2->getPortArrivee() === $this) {
                $liaisons2->setPortArrivee(null);
            }
        }

        
        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
