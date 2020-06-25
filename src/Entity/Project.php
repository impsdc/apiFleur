<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Entity\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use App\Controller\CreateMediaObjectAction;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;


/**
  * @ApiResource(
 *          itemOperations={"get", "put"={
 *                  "controller"=CreateMediaObjectAction::class,
 *                  "deserialize"=false, 
*               },
 * 
 *           "delete"}, 
 *          collectionOperations={"get",
 *              "post"={
 *                  "controller"=CreateMediaObjectAction::class,
 *                  "deserialize"=false, 
*               },
 *           }, 
*   normalizationContext={
*              "Groups"={"project"}  
*          }  
 * )
 * @ORM\HasLifecycleCallbacks()
 * @ApiFilter(SearchFilter::class, properties={"type": "ipartial"})
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("project")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, )
     * @Groups("project")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, )
     * @Groups("project")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups("project")
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255,)
     * @Groups({"project"})
     */
    public $filePath;

    /**
     * @ORM\ManyToOne(targetEntity=projectType::class, inversedBy="projects", cascade={"persist"} )
     * @ORM\JoinColumn()
     * @Groups({"project"})
     * 
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

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

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

    /**
     * @ORM\PreRemove
     */
    public function deleteFile($container)
    {
        $fileName = $this->getFilePath();

        $path = $this->$container->getParameter('uplaods_dir') . $fileName;

        $filesystem = new Filesystem();
        $filesystem->remove($path);
        return;
      

    }
}
