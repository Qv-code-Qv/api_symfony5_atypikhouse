<?php

namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController
{
    #[Route(path: '/api/login', name: 'api_login', methods: ['POST'])]
    public function login( UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $user = $userRepository->findOneBy([
            'email'=>$request->get('email'),
        ]);
        if (!$user || !$encoder->isPasswordValid($user, $request->get('password'))) {
            return $this->json([
                'message' => 'email or password is wrong.',
            ]);
        }
        
        return $this->json([
            'message' => 'success!',
            'token' => 'Bearer %s',
        ]);
    }


}