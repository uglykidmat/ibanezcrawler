<?php

namespace App\Entity;

use App\Repository\NeckRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NeckRepository::class)]
class Neck
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Type = null;

    #[ORM\Column(length: 255)]
    private ?string $Years = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ScaleLength = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $WidthAtNut = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $WidthAtLastFret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ThicknessAt1stFret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ThicknessAt12thFret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Radius = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getYears(): ?string
    {
        return $this->Years;
    }

    public function setYears(string $Years): static
    {
        $this->Years = $Years;

        return $this;
    }

    public function getScaleLength(): ?string
    {
        return $this->ScaleLength;
    }

    public function setScaleLength(?string $ScaleLength): static
    {
        $this->ScaleLength = $ScaleLength;

        return $this;
    }

    public function getWidthAtNut(): ?string
    {
        return $this->WidthAtNut;
    }

    public function setWidthAtNut(?string $WidthAtNut): static
    {
        $this->WidthAtNut = $WidthAtNut;

        return $this;
    }

    public function getWidthAtLastFret(): ?string
    {
        return $this->WidthAtLastFret;
    }

    public function setWidthAtLastFret(?string $WidthAtLastFret): static
    {
        $this->WidthAtLastFret = $WidthAtLastFret;

        return $this;
    }

    public function getThicknessAt1stFret(): ?string
    {
        return $this->ThicknessAt1stFret;
    }

    public function setThicknessAt1stFret(?string $ThicknessAt1stFret): static
    {
        $this->ThicknessAt1stFret = $ThicknessAt1stFret;

        return $this;
    }

    public function getThicknessAt12thFret(): ?string
    {
        return $this->ThicknessAt12thFret;
    }

    public function setThicknessAt12thFret(?string $ThicknessAt12thFret): static
    {
        $this->ThicknessAt12thFret = $ThicknessAt12thFret;

        return $this;
    }

    public function getRadius(): ?string
    {
        return $this->Radius;
    }

    public function setRadius(?string $Radius): static
    {
        $this->Radius = $Radius;

        return $this;
    }
}
