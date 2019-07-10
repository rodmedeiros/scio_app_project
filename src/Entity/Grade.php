<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GradeRepository")
 */
class Grade
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $degree;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EducationalLevel",
     *      inversedBy="grades",
     *      fetch="EXTRA_LAZY",
     *      cascade={"persist", "refresh"}
     *     )
     * @ORM\JoinColumn(nullable=false)
     */
    private $level;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Content", mappedBy="grade")
     */
    private $contents;

    //methods
    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getDegree()."º do E. ".$this->getLevel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDegree(): ?int
    {
        return $this->degree;
    }

    public function setDegree(int $degree): self
    {
        $this->degree = $degree;

        return $this;
    }

    public function getLevel(): ?EducationalLevel
    {
        return $this->level;
    }

    public function setLevel(?EducationalLevel $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|Content[]
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(Content $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents[] = $content;
            $content->addGrade($this);
        }

        return $this;
    }

    public function removeContent(Content $content): self
    {
        if ($this->contents->contains($content)) {
            $this->contents->removeElement($content);
            $content->removeGrade($this);
        }

        return $this;
    }
}
