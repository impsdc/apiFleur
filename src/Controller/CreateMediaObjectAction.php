<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ProjectTypeRepository;
use App\Repository\ProjectRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

final class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request, SerializerInterface $serializer, ProjectTypeRepository $repo, ProjectRepository $pro)
    {
        $data = $request->request;

        $mediaObject = new Project();
        
        $id = $repo->findOneBy([
            "id" =>     $request->get('type'), 
        ]);

        $projet = $pro->findOneBy([
            'type' => $id,
            'position' => $request->get('position')
        ]);

        if(is_null($projet)){
            $mediaObject->setType($id);
            $mediaObject->setName($request->get('name'));
            $mediaObject->setDescription($request->get('description'));
            $mediaObject->setPosition($request->get('position'));
            $image = $request->files->get('file');
            if (!$image) {
                throw new BadRequestHttpException('"file" is required');
            }
            if ($image) {
    
                $nomImage = uniqid() . '.' . $image->guessExtension();
    
                try {
                    $image->move(
                        $this->getParameter('uploads_dir'),
                        $nomImage
                    );
                } catch (FileException $e) {
                    return new BadRequestHttpException("cant moving file");
                }
                $mediaObject->setFilePath($nomImage);
            }
    
            return $mediaObject;
        }
        else{
            return new BadRequestHttpException("position already used");
        }
        
       
    }
}