<?php

namespace App\Controller\Admin;

use App\Entity\Comments;
use App\Entity\Equipement;
use App\Entity\Houses;
use App\Entity\Posts;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
   # #[Route('/admin', name:'admin')]
    /**
     * @Route("/admin", name="admin")
     * @return Response
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AtypikHouse');
    }

    public function configureMenuItems(): iterable
    {

       return [
           MenuItem::linkToLogout('Logout'),

           MenuItem::section('Users'),
           MenuItem::linkToCrud('Users', 'fa fa-user', User::class),

           MenuItem::section('Posts'),
           MenuItem::linkToCrud('Posts', 'fa fa-comments', Posts::class),
           MenuItem::linkToCrud('Comments', 'fa fa-comments', Comments::class),


           MenuItem::section('Houses'),
           MenuItem::linkToCrud('Houses', 'fa fa-home', Houses::class),

           MenuItem::section('Equipements'),
           MenuItem::linkToCrud('Equipements', 'fa fa-home', Equipement::class),

       ];
        // yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
