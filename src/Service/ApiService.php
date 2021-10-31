<?php

namespace App\Service;

use App\Entity\Houses;
use App\Entity\User;
use App\Repository\HousesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiService
{
    protected ?UserInterface $user;
    private EntityManagerInterface $em;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
    ){
        $this->user = $security->getUser();
        $this->em = $entityManager;

    }


    public function logout(User $user): array
    {
        $this->em->getRepository(User::class)->deleteRefreshToken(
            $user->getEmail()
        );
        return ["httpCode" => 200, "data" => ["message" => "OK"]];
    }

}