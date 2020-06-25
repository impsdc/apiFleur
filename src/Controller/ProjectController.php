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
     * @Route("/project/{id}", name="project_put", methods={"PUT", "POST"})
     */
    public function edit(Project $project, Request $request, projectRepository $repo, SerializerInterface $serializer, projectTypeRepository $repoProjectType)
    {
        $data = $request->getContent();
        dump($request);
        dump($request->get('type'));

        $mediaObject = new Project();
        
        $id = $repo->findOneBy([
            "id" =>     $request->get('type')
        ]);
        
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
                return new JsonResponse("cant moving file", Response::HTTP_BAD_REQUEST, [], true );
            }
            $mediaObject->setFilePath($nomImage);
        }
        
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
        $data = $request->getContent();
        dump($data);
        die;
     
        // $project = $serializer->deserialize($data, project::class, 'json' );

        $em = $this->getDoctrine()->getManager();
       
        $em->remove($project);
        $em->flush();

        return new JsonResponse('Informations has been deleted', 200, [
        
        ], true);
    }
}