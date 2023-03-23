<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher extends Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $dailyRate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDailyRate(): ?int
    {
        return $this->dailyRate;
    }

    public function setDailyRate(int $dailyRate): self
    {
        $this->dailyRate = $dailyRate;

        return $this;
    }
}
