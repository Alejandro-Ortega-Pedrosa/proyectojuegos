<?php
    namespace App\Controller;

    use App\Entity\Evento;
use App\Form\EventoType;
use App\Service\BotTelegram;
use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Request;


    class miController extends AbstractController{
        public function __construct(private ManagerRegistry $doctrine)
        {
        
        }

        #[Route('/', name: 'index')] 
        public function index():Response{

            return $this->render('landingPage.html.twig');
           
        }

        #[Route('/telegram', name: 'telegram')] 
        public function telegram(BotTelegram $botTelegram):Response{

            $botTelegram->main();

            return $this->render('prueba.html.twig');
           
        }

        #[Route('/gestionMesas', name: 'gestionMesas')] 
        public function gestionMesas():Response{

            return $this->render('gestionMesas.html.twig');
           
        }

        #[Route('/gestionReservas', name: 'gestionReservas')] 
        public function gestionReservas():Response{

            return $this->render('gestionReservas.html.twig');
           
        }

        #[Route('/gestionEventos/{id}', name: 'gestionEventos')] 
        public function gestionEventos(int $id):Response{


            return $this->render('gestionEventos.html.twig', ['id' => $id]);
           
        }

         
        #[Route('/eventos', name: 'eventos')] 
        public function eventos(Request $request,  EntityManagerInterface $em):Response{

            $eventos = $this->doctrine
            ->getRepository(Evento::class)
            ->findAll();

             //FORMULARIO
             $evento = new Evento();
    
             $form = $this->createForm(EventoType::class, $evento);
     
             $form->handleRequest($request);
 
             if ($form->isSubmitted() && $form->isValid()) {
 
                 $evento = $form->getData();    
 
                 $em->persist($evento);
                 $em->flush();
             }

            return $this->render('eventos.html.twig', [
                'eventos' => $eventos,
                'form' => $form
            ]);
           
        }

        

       
    }

    