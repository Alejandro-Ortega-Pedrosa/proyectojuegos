<?php
    namespace App\Controller;

    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class miController extends AbstractController{
        public function __construct(private ManagerRegistry $doctrine)
        {
        
        }

        #[Route('/', name: 'index')] 
        public function index():Response{

            return $this->render('landingPage.html.twig');
           
        }

        //SE EDITA LA DISTRIBUCION DE UNA MESA
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionMesas', name: 'gestionMesas')] 
        public function gestionMesas():Response{

            return $this->render('gestionMesas.html.twig');
           
        }

        //FORMULARIO PARA REALIZAR UNA NUEVA RESERVA 
        #[IsGranted('ROLE_USER')] 
        #[Route('/gestionReservas', name: 'gestionReservas')] 
        public function gestionReservas():Response{

            return $this->render('gestionReservas.html.twig');
           
        }

        //GESTION DE INVITACIONES A UN EVENTO
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionInvitaciones/{id}', name: 'gestionInvitacionesE')] 
        public function gestionInvitaciones(string $id):Response{

            return $this->render('gestionInvitaciones.html.twig',[
                'id' => $id,
            ]);
           
        }

        //GESTION DE RESERVAS DEL USUARIO ACTUAL
        #[IsGranted('ROLE_USER')] 
        #[Route('/gestionMisReservas', name: 'gestionMisReservas')] 
        public function gestionMisReservas():Response{

            return $this->render('gestionMisReservas.html.twig');
           
        }

        
        //GESTION DE RESERVAS DEL USUARIO ACTUAL
        #[IsGranted('ROLE_USER')] 
        #[Route('/video', name: 'video')] 
        public function video():Response{

            return $this->render('video.html.twig');
           
        }

        
       
    }

    