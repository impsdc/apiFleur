<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectTypeRepository;
use App\Entity\ProjectType;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectTypeController extends AbstractController
{
    /**
     * @Route("/projectType", name="projectType_get", methods={"GET"})
     */
    public function index(projectTypeRepository $repo, SerializerInterface $serializer)
    {
       $projectType = $repo->findAll();
       $resultat = $serializer->serialize(
           $projectType,
           'json',
           [
               'groups' => ['projectTypeAll']
           ]
           );
        return new JsonResponse($resultat, 200, [], true);
    }

     /**
     * @Route("/projectType/{id}", name="projectType_show", methods={"GET"})
     */
    public function show(ProjectType $projectType, projectTypeRepository $repo, SerializerInterface $serializer)
    {
       $resultat = $serializer->serialize(
           $projectType,
           'json',
           [
               'groups' => ['projectTypeAll']
           ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/projectType", name="projectType_post", methods={"POST"})
     */
    public function create(Request $request, projectTypeRepository $repo, SerializerInterface $serializer)
    {
        $data = $request->getContent();
     
        $projectType = $serializer->deserialize($data, projectType::class, 'json' );
         $em = $this->getDoctrine()->getManager();
         $em->persist($projectType);
         $em->flush();

        return new JsonResponse('Informations has been send succesfuly', 201, [
            "id" => $projectType->getId()
        ], true);
    }
}
