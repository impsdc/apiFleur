<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"projectTypeAll", "projectAll"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"projectTypeAll", "projectAll"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"projectTypeAll", "projectAll"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"projectTypeAll", "projectAll"})
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"projectTypeAll", "projectAll"})
     */
    private $media;

    /**
     * @ORM\OneToOne(targetEntity=ProjectType::class, inversedBy="project", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"projectAll"})
     */
    private $type;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): ?float
    {
        return $this->position;
    }

    public function setPosition(float $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getType(): ?ProjectType
    {
        return $this->type;
    }

    public function setType(ProjectType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
