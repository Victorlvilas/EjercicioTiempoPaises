<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 2, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $capital = null;

    #[ORM\Column(nullable: true)]
    private ?int $population = null;

    #[ORM\Column(nullable: true)]
    private ?float $countryLat = null;

    #[ORM\Column(nullable: true)]
    private ?float $countryLng = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(?string $capital): static
    {
        $this->capital = $capital;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(?int $population): static
    {
        $this->population = $population;

        return $this;
    }

    public function getCountryLat(): ?float
    {
        return $this->countryLat;
    }

    public function setCountryLat(?float $countryLat): static
    {
        $this->countryLat = $countryLat;

        return $this;
    }

    public function getCountryLng(): ?float
    {
        return $this->countryLng;
    }

    public function setCountryLng(?float $countryLng): static
    {
        $this->countryLng = $countryLng;

        return $this;
    }
}
