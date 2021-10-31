<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class GoogleAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    private $messageTrans = [
        "Username could not be found." =>
            "Soit vous n'êtes pas autorisé à acccéder à cette application ou alors veuillez vous connecter " .
            "normalement et lier votre compte google depuis votre profil"
    ];
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $tokenStorage;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * GoogleAuthenticator constructor.
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $em
     * @param UserManagerInterface $userManager
     */
    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        UserManagerInterface $userManager,
        FlashBagInterface $flashBag,
        TokenStorageInterface $tokenStorage
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->userManager = $userManager;
        $this->flashBag = $flashBag;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    /**
     * @param Request $request
     * @return \League\OAuth2\Client\Token\AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getGoogleClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|null|object|\Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        $email = $googleUser->getEmail();

        // 1) have they logged in with Google before? Easy!
        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['googleId' => $googleUser->getId()]);

        if ($existingUser) {
            $user = $existingUser;
        } else {
            $user = $this->em->getRepository(User::class)
                ->findOneBy(['email' => $email]);
            if (!$user) {
                return null;
            }
        }

        // 3) Maybe you just want to "register" them by creating
        // a User object
        $user->setGoogleId($googleUser->getId());
        $this->userManager->updateUser($user);

        return $userProvider->loadUserByUsername($user->getUsername());
    }

    /**
     * @return mixed
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null|Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return null|Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = trim(strtr($exception->getMessageKey(), $exception->getMessageData()));
        $this->flashBag->set('danger', $this->messageTrans[$message] ?? $message);
        return new RedirectResponse(
            '/login/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
        //return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     *
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {

        return new RedirectResponse(
            '/login/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
