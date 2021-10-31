<?php

namespace App\Controller\api;

use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Categories;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    public function __construct(private \Symfony\Component\Security\Core\Security $security)
    {
    }
    public function __invoke(User $user)
    {
        $user = $this->security->getUser();
        return $user;
    }

    /**
     * @Route("/profile/{id}", name="api_logged_user_profile",
     *      methods={"GET"})
     * @return JsonResponse
     */
    public function profile(User $user)
    {
        return new JsonResponse(["user" => $user->__draw()]);
    }

    /**
     * @Route("/user/logout", name="api_logged_user_logout", methods={"POST"})
     * @param ApiService $userService
     * @return JsonResponse|AccessDeniedHttpException
     */
    public function logout(ApiService $userService)
    {
        $user = $this->getUser();
        if (null == $user) {
            throw new AccessDeniedHttpException("User not found");
        }
        $response = $userService->logout($user);
        return new JsonResponse($response["data"], $response["httpCode"]);
    }


    /**
     * Permet d'authentifier un utilisateur dans l'application
     * @Route("/auth", name="auth_api", methods="post")
     */
    public function connexion_post(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        if(!empty($request->request->get('bearer')) && $request->request->get('bearer') === $this->getParameter('bearer')) {
            if(!empty($request->request->get('username'))) {
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $request->request->get('username')]);
                if(!empty($user) && !empty($request->request->get('password'))) {
                    if($passwordEncoder->isPasswordValid($user, $request->request->get('password'))) {
                        $dataUser = ['id' => $user->getId(), 'nom' => $user->getLastname(), 'prenom' => $user->getFirstname(), 'adresse' => $user->getAdresse(), 'email' => $user->getEmail()];
                        $response->setStatusCode(200);
                        $response->setContent(json_encode($dataUser));
                    } else {
                        $response->setContent(json_encode('Mot de passe erronné.'));
                    }
                } else {
                    $response->setStatusCode(404);
                    $response->setContent(json_encode('Not Found'));
                }
            } else {
                $response->setContent(json_encode('Veuillez rentrez un identifiant.'));
            }
        } else {
            $response->setStatusCode(401);
            $response->setContent(json_encode('Unauthorized'));
        }
        return $response;
    }

    /**
     * Permet d'obtenir les types de logement dans l'application
     * @Route("/types-logements", name="types_api", methods="post")
     */
    public function types_logement(Request $request) {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        if(!empty($request->request->get('tokenApi')) && $request->request->get('tokenApi') === $this->getParameter('api_token')) {
            $types = $this->getDoctrine()->getRepository(Categories::class)->findAllTypes();
            $response->setStatusCode(200);
            $response->setContent(json_encode($types));
        } else {
            $response->setStatusCode(401);
            $response->setContent(json_encode('Unauthorized'));
        }
        return $response;
    }

    /**
     * Permet d'obtenir les types de logement dans l'application
     * @Route("/search", name="search_api", methods="post")
     */
    public function search_post(Request $request) {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        if(!empty($request->request->get('tokenApi')) && $request->request->get('tokenApi') === $this->getParameter('api_token')) {
            $repoSearch = $this->getDoctrine()->getRepository(Logement::class);
            if(!empty($request->request->get('ville'))) {
                $ville = $this->getDoctrine()->getRepository(Ville::class)->findOneBy(['nom' => $request->request->get('ville')]);
                if(!empty($ville)) {
                    $logements = $repoSearch->findByCriteresApi(array('type' => $request->request->get('type'), 'nb' => $request->request->get('nb'), 'etat' => 1, 'ville' => $ville));
                } else {
                    $logements = $repoSearch->findByCriteresApi(array('type' => $request->request->get('type'), 'nb' => $request->request->get('nb'), 'etat' => 1));
                }
            } else {
                $logements = $repoSearch->findByCriteresApi(array('type' => $request->request->get('type'), 'nb' => $request->request->get('nb'), 'etat' => 1));
            }
            $response->setContent(json_encode($logements));
        } else {
            $response->setStatusCode(401);
            $response->setContent(json_encode('Unauthorized'));
        }
        return $response;
    }

}
