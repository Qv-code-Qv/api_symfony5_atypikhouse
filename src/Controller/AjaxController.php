<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/ajax")
 */
Class AjaxController extends AbstractController
{

    /**
     * @Route("/getParamsByType", name="ajax_getparams")
     * Permet de récupérer les paramètres d'un type
     * @return  JSON | String
     */
    public function getParamsByType(Request $request)
    {
        if (!empty($request->request->get('type'))) {
            $params = $this->getDoctrine()->getRepository(ParametresType::class)->findByType($request->request->get('type'));
            return new Response(json_encode($params));
        } else {
            return new Response("Erreur");
        }
    }
}