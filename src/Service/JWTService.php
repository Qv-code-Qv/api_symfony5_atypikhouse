<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class JWTService
{
    /** @var JWTTokenManagerInterface */
    private JWTTokenManagerInterface $tokenManager;

    /** @var RefreshTokenManagerInterface */
    private RefreshTokenManagerInterface $refreshTokenManager;

    /** @var ValidatorInterface */
    private ValidatorInterface $validator;

    /** @var int */
    private int $ttl;

    public function __construct(
        JWTTokenManagerInterface $tokenManager,
        RefreshTokenManagerInterface $refreshTokenManager,
        ValidatorInterface $validator,
        int $ttl
    ) {
        $this->tokenManager = $tokenManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->validator = $validator;
        $this->ttl = $ttl;
    }

    public function createNewJWT(User $user): array
    {
        $token = $this->tokenManager->create($user);

        $datetime = new \DateTime();
        $datetime->modify('+' . $this->ttl . ' seconds');

        $refreshToken = $this->refreshTokenManager->create();

        $refreshToken->setUsername($user->getUsername());
        $refreshToken->setRefreshToken();
        $refreshToken->setValid($datetime);

        $valid = false;
        while (false === $valid) {
            $valid = true;
            $errors = $this->validator->validate($refreshToken);
            if ($errors->count() > 0) {
                foreach ($errors as $error) {
                    if ('refreshToken' === $error->getPropertyPath()) {
                        $valid = false;
                        $refreshToken->setRefreshToken();
                    }
                }
            }
        }
        $this->refreshTokenManager->save($refreshToken);
        return ["token" => $token,
            "refresh_token" => $refreshToken->getRefreshToken()];
    }
}