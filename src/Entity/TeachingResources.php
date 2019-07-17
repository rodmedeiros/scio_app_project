<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeachingResourcesRepository")
 * @ORM\Table(name="educational_resources", indexes={@ORM\Index(name="type_idx", columns={"type"})})
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=3)
 * @ORM\DiscriminatorMap({
 *     "Exc"="Exercise",
 *     "Par"="Paper",
 *     "PPP"="PedagogicalProject",
 *     "Flm"="Film",
 *     "LeP"="LessonPlain",
 *     "ScP"="SchoolProject",
 *     "Sld"="Slides"
 * })
 */
abstract class TeachingResources
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Content", inversedBy="teachingResources")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="teachingResources")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $profile;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $downloads;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(?Content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDownloads(): ?int
    {
        return $this->downloads;
    }

    public function setDownloads(?int $downloads): self
    {
        $this->downloads = $downloads;

        return $this;
    }
}
