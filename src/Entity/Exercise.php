<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExerciseRepository")
 */
class Exercise extends TeachingResources
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $objective;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $aswer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjective(): ?bool
    {
        return $this->objective;
    }

    public function setObjective(bool $objective): self
    {
        $this->objective = $objective;

        return $this;
    }

    public function getAswer(): ?string
    {
        return $this->aswer;
    }

    public function setAswer(string $aswer): self
    {
        $this->aswer = $aswer;

        return $this;
    }
}
