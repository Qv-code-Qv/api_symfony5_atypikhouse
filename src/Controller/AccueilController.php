<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;




class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    final public function home(): Response
    {
        $form = $this->createForm(ContactType::class);

        return $this->render("dashboard.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @Route("/hosting", name="new_hosting")
     */
    public function host()
    {
        return $this->render("hosting/accueil.html.twig");
    }


    /**
     * @Route("/activities", name="activities")
     */
    public function activities()
    {
        return $this->render("activities/activities.html.twig");
    }



}