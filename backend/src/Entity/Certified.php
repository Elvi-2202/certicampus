<?php

namespace App\Entity;

use App\Repository\CertifiedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertifiedRepository::class)]
class Certified
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 50)]
    private ?string $grade = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $graduation_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): static
    {
        $this->grade = $grade;

        return $this;
    }

    public function getGraduationDate(): ?\DateTimeImmutable
    {
        return $this->graduation_date;
    }

    public function setGraduationDate(\DateTimeImmutable $graduation_date): static
    {
        $this->graduation_date = $graduation_date;

        return $this;
    }
}
