<?php
 
    namespace App\Controller;

use App\Entity\Juego;
use App\Entity\Mesa;
use App\Entity\Reserva;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Component\HttpFoundation\Request;

#[Route(path:'/api', name:'api_')]
class apiReserva extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine)
    {
        
    }

     //CREA UNA NUEVA MESA SEGUN LOS DATOS PASADOS POR POST
     #[Route(path:'/reserva', name:"reserva_new", methods:'POST')]
     public function new(Request $request): Response
     {
         $entityManager = $this->doctrine->getManager();

         $mesa = $this->doctrine
         ->getRepository(Mesa::class)
         ->find($request->request->get('mesa'));
         
         $juego = $this->doctrine
         ->getRepository(Juego::class)
         ->find($request->request->get('juego'));
 
 
         //CREO LA NUEVA MESA CON LAS PROPIEDADES 
         $reserva = new Reserva();
         $reserva->setFecha($request->request->get('fecha'));
         $reserva->setMesa($mesa);
         $reserva->setJuego($juego);
         $reserva->setUser($this->getUser());
                
         //LA GUARDO EN LA BASE DE DATOS
         $entityManager->persist($reserva);
         $entityManager->flush();
  
         return $this->json('Created new project successfully with id ' . $reserva->getId());
     }

}