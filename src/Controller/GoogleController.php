<?php

namespace App\Controller;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     * @param ClientRegistry $clientRegistry
     *
     * @Route("/google-connect", name="connect_google_start")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect([
                'profile', 'email' // the scopes you want to access
            ], [])
            ;
    }


    /**
     *
     * @Route("/google/logout", name="logout_google")
     *
     */
    public function logoutAction(UserManagerInterface $userManager, Request $request)
    {
        $user = $this->getUser();
        if ($user->getGoogleId() != null) {
            $user->setGoogleId(null);
            $userManager->updateUser($user);
            $this->addFlash('success', 'Déconnexion réussi du service Google');
        } else {
            $this->addFlash('danger', 'Vous êtes déjà déconnecté du service Google');
        }

        return $this->redirectToRoute("easyadmin", ["entity" => "User",
            "action" => "show", "id" => $user->getId()]);
    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     *
     * @Route("/connect/google/check", name="connect_google_check")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|JsonResponse
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        if (!$this->getUser()) {
            return new JsonResponse(array('status' => false, 'message' => "User not found!"));
        } else {
            return $this->redirectToRoute('dashboard');
        }
    }
}
