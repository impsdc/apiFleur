<?php

namespace App\Entity;

use App\Entity\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
  * @ApiResource(
 *          itemOperations={"get", "put", "delete"},
 *          collectionOperations={"get", 
    *          "post"={
    *             "controller"=CreateMediaObjectAction::class,
    *             "deserialize"=false,
    *            
    *             "openapi_context"={
    *                 "requestBody"={
    *                     "content"={
    *                         "multipart/form-data"={
    *                             "schema"={
    *                                 "type"="object",
    *                                 "properties"={
    *                                     "file"={
    *                                         "type"="string",
    *                                         "format"="binary"
    *                                     }
    *                                 }
    *                             }
    *                         }
    *                     }
    *                 }
    *             }
    *         },
 * 
 *           }, 
 *          attributes={
 *               "order"={
*                   "position": "DESC"
 *              }
 *          }
 * )
 * 
 * @ApiFilter(SearchFilter::class, properties={"type": "exact"})
 * 
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"projectType"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"projectType"})
     */
    private $description;

    /**
     * @ORM\Column(type="float", unique=true)
     * @Groups({"projectType"})
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255) 
     * @Groups({"projectType"})
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=projectType::class, inversedBy="projects", cascade={"persist"} )
     * @ORM\JoinColumn(nullable=false)
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
