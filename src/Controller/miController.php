<?php
    namespace App\Controller;

    use App\Service\apiTiempo;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class miController extends AbstractController{
        #[Route('/', name: 'index')]
        public function landingPage():Response{

            return $this->render('index.html.twig');
        }

        #[Route('/home', name: 'home')]
        public function homePage():Response{

            return $this->render('home.html.twig');
        }

        #[Route('/apiTiempo', name: 'tiempo')]
        public function apiTiempo(apiTiempo $apiTiempo):Response{

            $respuesta=$apiTiempo->getTiempoJaen();

            return $this->render('ejemploApi.html.twig',['respuesta'=> $respuesta]);

        }

        #[Route('/gestionMesas', name: 'gestionMesas')] 
        public function ejemploResponse():Response{

            return $this->render('gestionMesas.html.twig');
           
        }


       
    }

    