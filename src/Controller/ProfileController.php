<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Class ProfileController
 * @package App\Controller
 */
class ProfileController extends AbstractController
{
    public function __construct(private \Symfony\Component\Security\Core\Security $security)
    {
    }
    public function __invoke()
    {
        $user = $this->security->getUser();
        return $user;
    }


    /**
     * Show the user.
     * @return Response
     * @Route("/profilee", name="profil", methods="post|get")
     * @IsGranted("ROLE_USER")
     */
    public function showAction(Request $request): Response
    {
        return $this->render('profile/profile.html.twig');
    }

    /**
     * @Route("/show", name="account_profile_show_himself")
     * @param UserService $userService
     * @param Session $session
     * @return Response
     */
    final public function showHimself(UserService $userService, Session $session): Response
    {
        $user = $this->getUser();
        if (null === $user) {
            $this->redirectToRoute("app_logout");
        }

        $result = $userService->formUser($user);
        $form = $result["form"];

        if ($result["code"] !== 001) {
            $session->getFlashBag()->set(($result["code"] == 200? "success" : "danger"), $result["message"]);
        }

        return  $result["code"] == 200? $this->redirectToRoute('account_profile_show_himself') :
            $this->render("profile/profile.html.twig", ["form" => $form->createView()]);
    }



}