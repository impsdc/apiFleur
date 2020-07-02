<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use App\Entity\Contact;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact/post", name="contact_post", methods={"POST"})
     */
    public function create(Request $request, ContactRepository $repo, SerializerInterface $serializer, ValidatorInterface $validator )
    {
        $data = $request->getContent();
        $contact = $serializer->deserialize($data, Contact::class, 'json' );
        $contact->setDate(new \Datetime("NOW"));
        // $contact->setDate('lol');
        //erreur de validation
        $errors = $validator->validate($contact);
        if(count($errors)){
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse("error", Response::HTTP_BAD_REQUEST, [], true );
        }
     
        
       
         $em = $this->getDoctrine()->getManager();
         $em->persist($contact);
         $em->flush();

        return new JsonResponse('Informations has been send succesfuly', 201, [
            "id" => $contact->getId()
        ], true);
    }

}
