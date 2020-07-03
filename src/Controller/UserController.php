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
     * @Route("api/register", name="register", methods={"POST"})
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request, SerializerInterface $serializer)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $data = $request->getcontent();

            $userData = $serializer->deserialize($data, User::class, 'json' );

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
        
            return new JsonResponse('log in success', 201, [
                
            ], true);
        }
        else{
            return new JsonResponse('please log in', 503, [
                
           ], true);
        }
        
        
    }
}
