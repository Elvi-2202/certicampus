<?php

namespace App\Entity;

use App\Repository\DiplomaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiplomaRepository::class)]
class Diploma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $file_url = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $generated_at = null;

    #[ORM\Column]
    private ?bool $is_valid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileUrl(): ?string
    {
        return $this->file_url;
    }

    public function setFileUrl(string $file_url): static
    {
        $this->file_url = $file_url;

        return $this;
    }

    public function getGeneratedAt(): ?\DateTimeImmutable
    {
        return $this->generated_at;
    }

    public function setGeneratedAt(\DateTimeImmutable $generated_at): static
    {
        $this->generated_at = $generated_at;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(bool $is_valid): static
    {
        $this->is_valid = $is_valid;

        return $this;
    }
}
