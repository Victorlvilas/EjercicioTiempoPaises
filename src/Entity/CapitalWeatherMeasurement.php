<?php

namespace App\Entity;

use App\Repository\CapitalWeatherMeasurementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CapitalWeatherMeasurementRepository::class)]
class CapitalWeatherMeasurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?float $weatherLat = null;

    #[ORM\Column(nullable: true)]
    private ?float $weatherLng = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $measuredAt = null;

    #[ORM\ManyToOne]
    private ?Country $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(?float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getWeatherLat(): ?float
    {
        return $this->weatherLat;
    }

    public function setWeatherLat(?float $weatherLat): static
    {
        $this->weatherLat = $weatherLat;

        return $this;
    }

    public function getWeatherLng(): ?float
    {
        return $this->weatherLng;
    }

    public function setWeatherLng(?float $weatherLng): static
    {
        $this->weatherLng = $weatherLng;

        return $this;
    }

    public function getMeasuredAt(): ?\DateTimeImmutable
    {
        return $this->measuredAt;
    }

    public function setMeasuredAt(\DateTimeImmutable $measuredAt): static
    {
        $this->measuredAt = $measuredAt;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
