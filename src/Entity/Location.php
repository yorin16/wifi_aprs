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
    private ?string $coordinate = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(name: 'device_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Device $Device = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $Project;

    #[ORM\OneToOne(mappedBy: 'Location', cascade: ['persist', 'remove'])]
    private ?Question $question = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoordinate(): ?string
    {
        return $this->coordinate;
    }

    public function setCoordinate(string $coordinate): self
    {
        $this->coordinate = $coordinate;

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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        // unset the owning side of the relation if necessary
        if ($question === null && $this->question !== null) {
            $this->question->setLocation(null);
        }

        // set the owning side of the relation if necessary
        if ($question !== null && $question->getLocation() !== $this) {
            $question->setLocation($this);
        }

        $this->question = $question;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
