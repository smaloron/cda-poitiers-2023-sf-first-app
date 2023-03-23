<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $enrolledAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnrolledAt(): ?\DateTimeInterface
    {
        return $this->enrolledAt;
    }

    public function setEnrolledAt(\DateTimeInterface $enrolledAt): self
    {
        $this->enrolledAt = $enrolledAt;

        return $this;
    }
}
