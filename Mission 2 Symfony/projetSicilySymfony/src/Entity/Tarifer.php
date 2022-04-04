<?php

namespace App\Entity;

use App\Repository\TariferRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TariferRepository::class)
 */
class Tarifer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $tarif;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="tarifers")
     */
    private $Type;

    /**
     * @ORM\ManyToOne(targetEntity=Periode::class, inversedBy="tarifers")
     */
    private $Periode;

    /**
     * @ORM\ManyToOne(targetEntity=Liaison::class, inversedBy="tarifers")
     */
    private $Liaison;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->Type;
    }

    public function setType(?Type $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getPeriode(): ?Periode
    {
        return $this->Periode;
    }

    public function setPeriode(?Periode $Periode): self
    {
        $this->Periode = $Periode;

        return $this;
    }

    public function getLiaison(): ?Liaison
    {
        return $this->Liaison;
    }

    public function setLiaison(?Liaison $Liaison): self
    {
        $this->Liaison = $Liaison;

        return $this;
    }
}
