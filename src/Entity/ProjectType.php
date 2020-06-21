<?php

namespace App\Entity;

use App\Repository\ProjectTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=ProjectTypeRepository::class)
 */
class ProjectType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"rojectTypeAll", "projectAll"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"projectTypeAll","projectAll"})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Project::class, mappedBy="type", cascade={"persist", "remove"})
     * @Groups({"projectTypeAll"})
     * 
     */
    private $project;

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        // set the owning side of the relation if necessary
        if ($project->getType() !== $this) {
            $project->setType($this);
        }

        return $this;
    }

    public function __toString() : String
    {
        return $this->getName();
    }
}
