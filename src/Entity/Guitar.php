<?php

namespace App\Entity;

use App\Repository\GuitarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuitarRepository::class)]
class Guitar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modelName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $soldin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $madein = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bodytype = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bodymaterial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $neckjoint = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $knobstyle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hardwarecolor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $necktype = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $neckmaterial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $scalelength = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fingerboardmaterial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fingerboardinlays = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $machineheads = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pickupconfiguration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bridgepickup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $middlepickup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $neckpickup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $outputjack = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $factorytuning = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $strapbuttons = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Yearsoffered = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Finishes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Bridge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Frets = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Nut = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Controls = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Stringgaugefactory = null;

    #[ORM\Column(length: 255)]
    private ?string $family = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Switch = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Battery = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Tailpiece = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Pickguard = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Dimensions = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Top = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Sides = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Electronics = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extraParenthesisInfo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getModelname(): ?string
    {
        return $this->modelName;
    }

    public function setModelname(string $modelName): static
    {
        $this->modelName = $modelName;

        return $this;
    }

    public function getSoldin(): ?string
    {
        return $this->soldin;
    }

    public function setSoldin(string $soldin): static
    {
        $this->soldin = $soldin;

        return $this;
    }

    public function getMadein(): ?string
    {
        return $this->madein;
    }

    public function setMadein(string $madein): static
    {
        $this->madein = $madein;

        return $this;
    }

    public function getBodytype(): ?string
    {
        return $this->bodytype;
    }

    public function setBodytype(string $bodytype): static
    {
        $this->bodytype = $bodytype;

        return $this;
    }

    public function getBodymaterial(): ?string
    {
        return $this->bodymaterial;
    }

    public function setBodymaterial(string $bodymaterial): static
    {
        $this->bodymaterial = $bodymaterial;

        return $this;
    }

    public function getNeckjoint(): ?string
    {
        return $this->neckjoint;
    }

    public function setNeckjoint(string $neckjoint): static
    {
        $this->neckjoint = $neckjoint;

        return $this;
    }

    public function getKnobstyle(): ?string
    {
        return $this->knobstyle;
    }

    public function setKnobstyle(string $knobstyle): static
    {
        $this->knobstyle = $knobstyle;

        return $this;
    }

    public function getHardwarecolor(): ?string
    {
        return $this->hardwarecolor;
    }

    public function setHardwarecolor(string $hardwarecolor): static
    {
        $this->hardwarecolor = $hardwarecolor;

        return $this;
    }

    public function getNecktype(): ?string
    {
        return $this->necktype;
    }

    public function setNecktype(string $necktype): static
    {
        $this->necktype = $necktype;

        return $this;
    }

    public function getNeckmaterial(): ?string
    {
        return $this->neckmaterial;
    }

    public function setNeckmaterial(string $neckmaterial): static
    {
        $this->neckmaterial = $neckmaterial;

        return $this;
    }

    public function getScalelength(): ?string
    {
        return $this->scalelength;
    }

    public function setScalelength(string $scalelength): static
    {
        $this->scalelength = $scalelength;

        return $this;
    }

    public function getFingerboardmaterial(): ?string
    {
        return $this->fingerboardmaterial;
    }

    public function setFingerboardmaterial(string $fingerboardmaterial): static
    {
        $this->fingerboardmaterial = $fingerboardmaterial;

        return $this;
    }

    public function getFingerboardinlays(): ?string
    {
        return $this->fingerboardinlays;
    }

    public function setFingerboardinlays(string $fingerboardinlays): static
    {
        $this->fingerboardinlays = $fingerboardinlays;

        return $this;
    }

    public function getMachineheads(): ?string
    {
        return $this->machineheads;
    }

    public function setMachineheads(string $machineheads): static
    {
        $this->machineheads = $machineheads;

        return $this;
    }

    public function getPickupconfiguration(): ?string
    {
        return $this->pickupconfiguration;
    }

    public function setPickupconfiguration(string $pickupconfiguration): static
    {
        $this->pickupconfiguration = $pickupconfiguration;

        return $this;
    }

    public function getBridgepickup(): ?string
    {
        return $this->bridgepickup;
    }

    public function setBridgepickup(?string $bridgepickup): static
    {
        $this->bridgepickup = $bridgepickup;

        return $this;
    }

    public function getMiddlepickup(): ?string
    {
        return $this->middlepickup;
    }

    public function setMiddlepickup(?string $middlepickup): static
    {
        $this->middlepickup = $middlepickup;

        return $this;
    }

    public function getNeckpickup(): ?string
    {
        return $this->neckpickup;
    }

    public function setNeckpickup(?string $neckpickup): static
    {
        $this->neckpickup = $neckpickup;

        return $this;
    }

    public function getOutputjack(): ?string
    {
        return $this->outputjack;
    }

    public function setOutputjack(?string $outputjack): static
    {
        $this->outputjack = $outputjack;

        return $this;
    }

    public function getFactorytuning(): ?string
    {
        return $this->factorytuning;
    }

    public function setFactorytuning(?string $factorytuning): static
    {
        $this->factorytuning = $factorytuning;

        return $this;
    }

    public function getStrapbuttons(): ?string
    {
        return $this->strapbuttons;
    }

    public function setStrapbuttons(?string $strapbuttons): static
    {
        $this->strapbuttons = $strapbuttons;

        return $this;
    }

    public function getYearsoffered(): ?string
    {
        return $this->Yearsoffered;
    }

    public function setYearsoffered(?string $Yearsoffered): static
    {
        $this->Yearsoffered = $Yearsoffered;

        return $this;
    }

    public function getFinishes(): ?string
    {
        return $this->Finishes;
    }

    public function setFinishes(?string $Finishes): static
    {
        $this->Finishes = $Finishes;

        return $this;
    }

    public function getBridge(): ?string
    {
        return $this->Bridge;
    }

    public function setBridge(?string $Bridge): static
    {
        $this->Bridge = $Bridge;

        return $this;
    }

    public function getFrets(): ?string
    {
        return $this->Frets;
    }

    public function setFrets(?string $Frets): static
    {
        $this->Frets = $Frets;

        return $this;
    }

    public function getNut(): ?string
    {
        return $this->Nut;
    }

    public function setNut(?string $Nut): static
    {
        $this->Nut = $Nut;

        return $this;
    }

    public function getControls(): ?string
    {
        return $this->Controls;
    }

    public function setControls(?string $Controls): static
    {
        $this->Controls = $Controls;

        return $this;
    }

    public function getStringgaugefactory(): ?string
    {
        return $this->Stringgaugefactory;
    }

    public function setStringgaugefactory(?string $Stringgaugefactory): static
    {
        $this->Stringgaugefactory = $Stringgaugefactory;

        return $this;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): static
    {
        $this->family = $family;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSwitch(): ?string
    {
        return $this->Switch;
    }

    public function setSwitch(?string $Switch): static
    {
        $this->Switch = $Switch;

        return $this;
    }

    public function getBattery(): ?string
    {
        return $this->Battery;
    }

    public function setBattery(?string $Battery): static
    {
        $this->Battery = $Battery;

        return $this;
    }

    public function getTailpiece(): ?string
    {
        return $this->Tailpiece;
    }

    public function setTailpiece(?string $Tailpiece): static
    {
        $this->Tailpiece = $Tailpiece;

        return $this;
    }

    public function getPickguard(): ?string
    {
        return $this->Pickguard;
    }

    public function setPickguard(?string $Pickguard): static
    {
        $this->Pickguard = $Pickguard;

        return $this;
    }

    public function getDimensions(): ?string
    {
        return $this->Dimensions;
    }

    public function setDimensions(?string $Dimensions): static
    {
        $this->Dimensions = $Dimensions;

        return $this;
    }

    public function getTop(): ?string
    {
        return $this->Top;
    }

    public function setTop(?string $Top): static
    {
        $this->Top = $Top;

        return $this;
    }

    public function getSides(): ?string
    {
        return $this->Sides;
    }

    public function setSides(?string $Sides): static
    {
        $this->Sides = $Sides;

        return $this;
    }

    public function getElectronics(): ?string
    {
        return $this->Electronics;
    }

    public function setElectronics(?string $Electronics): static
    {
        $this->Electronics = $Electronics;

        return $this;
    }

    public function getExtraParenthesisInfo(): ?string
    {
        return $this->extraParenthesisInfo;
    }

    public function setExtraParenthesisInfo(?string $extraParenthesisInfo): static
    {
        $this->extraParenthesisInfo = $extraParenthesisInfo;

        return $this;
    }

    public function getAllFields()
    {
        return get_object_vars($this);
    }
}
