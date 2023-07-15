<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'question', cascade: ['persist', 'remove'])]
    private ?Location $Location = null;

    #[ORM\Column(length: 1024)]
    private ?string $text = null;

    #[ORM\Column]
    private int $type;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Project $Project = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $multi1 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $multi2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $multi3 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $multi4 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $open = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answers::class, orphanRemoval: true)]
    private Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->Location;
    }

    public function setLocation(?Location $Location): self
    {
        $this->Location = $Location;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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

    public function getProject(): ?Project
    {
        return $this->Project;
    }

    public function setProject(?Project $Project): self
    {
        $this->Project = $Project;

        return $this;
    }

    public function getMulti1(): ?string
    {
        return $this->multi1;
    }

    public function setMulti1(?string $multi1): self
    {
        $this->multi1 = $multi1;

        return $this;
    }

    public function getMulti2(): ?string
    {
        return $this->multi2;
    }

    public function setMulti2(?string $multi2): self
    {
        $this->multi2 = $multi2;

        return $this;
    }

    public function getMulti3(): ?string
    {
        return $this->multi3;
    }

    public function setMulti3(?string $multi3): self
    {
        $this->multi3 = $multi3;

        return $this;
    }

    public function getMulti4(): ?string
    {
        return $this->multi4;
    }

    public function setMulti4(?string $multi4): self
    {
        $this->multi4 = $multi4;

        return $this;
    }

    public function getOpen(): ?string
    {
        return $this->open;
    }

    public function setOpen(?string $open): self
    {
        $this->open = $open;

        return $this;
    }

    /**
     * @return Collection<int, Answers>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }
}
