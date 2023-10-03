<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\Column(nullable: true)]
    private ?int $multiAnswer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $open = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    private ?Location $ReceivedRandomLocation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getMultiAnswer(): ?int
    {
        return $this->multiAnswer;
    }

    public function setMultiAnswer(int $multiAnswer): self
    {
        $this->multiAnswer = $multiAnswer;

        return $this;
    }

    public function getOpen(): ?string
    {
        return $this->open;
    }

    public function setOpen(string $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getReceivedRandomLocation(): ?Location
    {
        return $this->ReceivedRandomLocation;
    }

    public function setReceivedRandomLocation(?Location $ReceivedRandomLocation): static
    {
        $this->ReceivedRandomLocation = $ReceivedRandomLocation;

        return $this;
    }
}
