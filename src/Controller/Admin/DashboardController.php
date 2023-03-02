<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use App\Entity\Juego;
use App\Entity\Mesa;
use App\Entity\Reserva;
use App\Entity\Tramo;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ProyectoJuegos');
    }

    public function configureMenuItems(): iterable
    {
        return[
            //MenuItem::linkToRoute('Salir');
            MenuItem::linkToRoute('Home', 'fa fa-home', 'index'),
        
            MenuItem::linkToCrud('Usuario', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Juego', 'fa fa-gamepad', Juego::class),
            MenuItem::linkToCrud('Mesa', 'fa fa-stop', Mesa::class),
            MenuItem::linkToCrud('Reserva', 'fa fa-book', Reserva::class),
            MenuItem::linkToCrud('Evento', 'fa fa-calendar', Evento::class),
            MenuItem::linkToCrud('Tramo', 'fa fa-clock-o', Tramo::class),
        ];
    }
}
