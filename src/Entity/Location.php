<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Coordinate = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(name: 'device_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Device $Device = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $Project;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoordinate(): ?string
    {
        return $this->Coordinate;
    }

    public function setCoordinate(string $Coordinate): self
    {
        $this->Coordinate = $Coordinate;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->Device;
    }

    public function setDevice(?Device $Device): self
    {
        $this->Device = $Device;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->Project;
    }

    public function setProject(Project $Project): self
    {
        $this->Project = $Project;

        return $this;
    }
}
