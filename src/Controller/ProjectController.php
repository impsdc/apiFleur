<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectRepository;
use App\Repository\ProjectTypeRepository;
use App\Entity\Project;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project", name="project_get", methods={"GET"})
     */
    public function index(projectRepository $repo, SerializerInterface $serializer)
    {
       $project = $repo->findAll();
       $resultat = $serializer->serialize(
           $project,
           'json',
           [
               'groups' => ['projectAll']
           ]
           );
        return new JsonResponse($resultat, 200, [], true);
    }

     /**
     * @Route("/project/{id}", name="project_show", methods={"GET"})
     */
    public function show(Project $project, projectRepository $repo, SerializerInterface $serializer)
    {
       $resultat = $serializer->serialize(
           $project,
           'json',
           [
               'groups' => ['projectAll']
           ]
        );
        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/project", name="project_post", methods={"POST"})
     */
    public function create(Request $request, projectRepository $repoProject, projectTypeRepository $repoProjectType, SerializerInterface $serializer)
    {
        $data = $request->getContent();

        // $dataInArray = $serialize->decode($data, 'json');
        // $projectType= $repoProjectType->find(["type"]['id']);

        $project = $serializer->deserialize($data, project::class, 'json' );
        // $project->setType($projectType);

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        return new JsonResponse('Informations has been send succesfuly', 201, [
            "id" => $project->getId()
        ], true);
    }

    /**
     * @Route("/project/{id}", name="project_put", methods={"PUT"})
     */
    public function edit(Project $project, Request $request, projectRepository $repo, SerializerInterface $serializer, projectTypeRepository $repoProjectType)
    {
        $data = $request->getContent();

        $dataInArray = $serialize->decode($data, 'json');
        $projectType= $repoProjectType->find(["type"]['id']);

        $project = $serializer->deserialize($data, project::class, 'json' );
        $project->setType($projectType);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();;

        return new JsonResponse('Informations has been send modify', 200, [
            "id" => $project->getId()
        ], true);
    }

    /**
     * @Route("/project/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(Project $project, Request $request, projectRepository $repo, SerializerInterface $serializer)
    {
        // $data = $request->getContent();
     
        // $project = $serializer->deserialize($data, project::class, 'json' );
        $em = $this->getDoctrine()->getManager();
       
        $em->remove($project);
        $em->flush();

        return new JsonResponse('Informations has been deleted', 200, [
        
        ], true);
    }
}