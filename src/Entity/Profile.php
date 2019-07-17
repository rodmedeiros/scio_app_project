<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subject", inversedBy="profiles")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\School", inversedBy="profiles")
     */
    private $school;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"}, inversedBy="profile")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeachingResources", mappedBy="profile")
     */
    private $teachingResources;

    public function __construct()
    {
        $this->teachingResources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

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

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|TeachingResources[]
     */
    public function getTeachingResources(): Collection
    {
        return $this->teachingResources;
    }

    public function addTeachingResource(TeachingResources $teachingResource): self
    {
        if (!$this->teachingResources->contains($teachingResource)) {
            $this->teachingResources[] = $teachingResource;
            $teachingResource->setProfile($this);
        }

        return $this;
    }

    public function removeTeachingResource(TeachingResources $teachingResource): self
    {
        if ($this->teachingResources->contains($teachingResource)) {
            $this->teachingResources->removeElement($teachingResource);
            // set the owning side to null (unless already changed)
            if ($teachingResource->getProfile() === $this) {
                $teachingResource->setProfile(null);
            }
        }

        return $this;
    }
}
