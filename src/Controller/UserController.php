<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository){

        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request, SerializerInterface $serializer, UserRepository $repo)
    {
        $data = $request->getcontent();

        $userData = $serializer->deserialize($data, User::class, 'json' );

        dump($data);

        $em = $this->getDoctrine()->getManager();
        $truc=$this->userRepository->findOneBy([
            "username" => $userData->getUsername(),
        ]);
        
        if($truc){
            return new JsonResponse('userName already exist',409, [], true );
        };


        $user = new User;

        $user->setUsername($userData->getUsername());
        $user->setPassword($encoder->encodePassword($user, $userData->getPassword()));

       
        $em->persist($user);
        $em->flush();
    
        return new JsonResponse('ok', 201, [
             "tqt"
        ], true);
    }
}
