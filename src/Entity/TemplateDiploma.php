<?php

namespace App\Entity;

use App\Repository\TemplateDiplomaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemplateDiplomaRepository::class)]
class TemplateDiploma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $studentName = null;

    #[ORM\Column(length: 255)]
    private ?string $schoolName = null;

    #[ORM\Column(length: 255)]
    private ?string $directorName = null;

    #[ORM\Column(length: 255)]
    private ?string $assistantDirectorName = null;

    #[ORM\Column(length: 255)]
    private ?string $identifier = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $certificateDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudentName(): ?string
    {
        return $this->studentName;
    }

    public function setStudentName(?string $studentName): static
    {
        $this->studentName = $studentName;
        return $this;
    }

    public function getSchoolName(): ?string
    {
        return $this->schoolName;
    }

    public function setSchoolName(?string $schoolName): static
    {
        $this->schoolName = $schoolName;
        return $this;
    }

    public function getDirectorName(): ?string
    {
        return $this->directorName;
    }

    public function setDirectorName(?string $directorName): static
    {
        $this->directorName = $directorName;
        return $this;
    }

    public function getAssistantDirectorName(): ?string
    {
        return $this->assistantDirectorName;
    }

    public function setAssistantDirectorName(?string $assistantDirectorName): static
    {
        $this->assistantDirectorName = $assistantDirectorName;
        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): static
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getCertificateDate(): ?\DateTimeInterface
    {
        return $this->certificateDate;
    }

    public function setCertificateDate(?\DateTimeInterface $certificateDate): static
    {
        $this->certificateDate = $certificateDate;
        return $this;
    }

    public function getLabel(): ?string
    {
        return 'Template #' . $this->getId();
    }
}