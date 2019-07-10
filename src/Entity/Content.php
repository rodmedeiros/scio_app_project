<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContentRepository")
 */
class Content
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject", inversedBy="contents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subject;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Grade", inversedBy="contents")
     */
    private $grade;

    public function __construct()
    {
        $this->grade = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection|Grade[]
     */
    public function getGrade(): Collection
    {
        return $this->grade;
    }

    public function addGrade(Grade $grade): self
    {
        if (!$this->grade->contains($grade)) {
            $this->grade[] = $grade;
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grade->contains($grade)) {
            $this->grade->removeElement($grade);
        }

        return $this;
    }
}
