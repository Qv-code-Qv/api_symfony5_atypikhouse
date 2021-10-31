<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\User;
use App\Security\EmailVerifier;
use App\Security\AuthAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private MailerInterface $mailer;

    public function __construct(EmailVerifier $emailVerifier, MailerInterface $mailer)
    {
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $mailer;
    }

    
    #[Route('/register', name: 'app_register', methods:['POST'])]
    public function register(Request $request,MailerInterface $mailer, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AuthAuthenticator $authenticator, ValidatorInterface $validator): JsonResponse
    {

        $user = $this->get('serializer')->deserialize($request->getContent(), User::class, 'json');


        // encode the plain password
        $parameters = json_decode($request->getContent(), true);

        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $parameters['plainPassword']
            )
        );

        //validation des données user, permet de prendre en compte les assert
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
           
            $errorsString = (string) $errors;
    
            return new JsonResponse($errorsString);
        }

        $user->addRole("ROLE_USER");

        //permet que les données soient dans la bdd
        $user->setIsActive(true);
        $user->setCreationDate(new \DateTime());
        $user->setLastLogin(new  \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse('The user is valid! Yes!');


    }



    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }



    
}
