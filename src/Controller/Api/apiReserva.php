<?php
 
    namespace App\Controller\Api;

    use App\Entity\Juego;
    use App\Entity\Mesa;
    use App\Entity\Reserva;
    use App\Entity\Tramo;
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

    //DEVUELVE UN JSON CON TODAS LAS RESERVAS DE LA BD
    #[Route(path:'/reserva', name:'reserva_index', methods:'GET')]
    public function index(): Response
    {
        //BUSCA TODAS LAS RESERVAS DE LA BD
        $reservas = $this->doctrine
            ->getRepository(Reserva::class)
            ->findAll();
 
        $data = [];
 
        //METE TODAS LAS RESERVAS EN EL ARRAY DATA
        foreach ($reservas as $reserva) {
           $data[] = [
                'id' => $reserva->getId(),
                'mesa' => $reserva->getMesa()->getId(),
                'tramo' => $reserva->getTramo()->getId(),
                'fecha' => $reserva->getFecha()
           ];
        }
 
        //DEVUELVE EL JSON CON LAS RESERVAS
        return $this->json($data);
    }

    //DEVUELVE UN JSON CON TODAS LAS RESERVAS DE UN USUARIO
    #[Route(path:'/reservasUser', name:'reservasUser', methods:'GET')]
    public function reservasUser(): Response
    {
        //BUSCA TODAS LAS RESERVAS DE LA BD SEGUN EL USUARIO QUE ESTA CONSULTANDO
        $reservas = $this->doctrine
            ->getRepository(Reserva::class)
            ->findBy(array('user'=>$this->getUser()));
 
        $data = [];
 
        //METE TODAS LAS RESERVAS EN EL ARRAY DATA
        foreach ($reservas as $reserva) {
           $data[] = [
                'id' => $reserva->getId(),
                'mesa' => $reserva->getMesa()->getId(),
                'fecha' => $reserva->getFecha(),
                'tramo' => $reserva->getTramo()->__toString(),
                'juego' => $reserva->getJuego()->__toString()
           ];
        }
 
        //DEVUELVE EL JSON CON LAS RESERVAS
        return $this->json($data);
    }


     //CREA UNA NUEVA RESERVA SEGUN LOS DATOS PASADOS POR POST
     #[Route(path:'/reserva', name:"reserva_new", methods:'POST')]
     public function new(Request $request): Response
     {
         $entityManager = $this->doctrine->getManager();

         //BUSCA EL TRAMO POR SU ID
         $tramo = $this->doctrine
         ->getRepository(Tramo::class)
         ->find($request->request->get('tramo'));

         //BUSCA LA MESA POR SU ID
         $mesa = $this->doctrine
         ->getRepository(Mesa::class)
         ->find($request->request->get('mesa'));
         
         //BUSCA EL JUEGO POR SU ID
         $juego = $this->doctrine
         ->getRepository(Juego::class)
         ->find($request->request->get('juego'));
 
 
         //CREO LA NUEVA RESERVA CON LAS PROPIEDADES 
         $reserva = new Reserva();
         $reserva->setFecha($request->request->get('fecha'));
         $reserva->setTramo($tramo);
         $reserva->setMesa($mesa);
         $reserva->setJuego($juego);
         $reserva->setPresentado(false);
         $reserva->setUser($this->getUser());
                
         //LA GUARDO EN LA BASE DE DATOS
         $entityManager->persist($reserva);
         $entityManager->flush();
  
         return $this->json('Created new project successfully with id ' . $reserva->getId());
     }

      //BORRA UNA RESERVA DE LA BASE DE DATOS SEGUN SU ID
      #[Route(path:'/reserva/{id}', name:"reserva_delete", methods:'DELETE')]
      public function delete(int $id): Response
      {
          $entityManager = $this->doctrine->getManager();
          //BUSCA LA RESERVA EN LA BASE DE DATOS
          $reserva = $entityManager->getRepository(Reserva::class)->find($id);
   
          //SI NO ENCUENTRA LA RESERVA DEVUELVE EL MENSAJE DE ERROR
          if (!$reserva) {
              return $this->json('No project found for id' . $id, 404);
          }
   
          //BORRA LA RESERVA DE LA BASE DE DATOS
          $entityManager->remove($reserva);
          $entityManager->flush();
   
          //UNA VEZ BORRADA DEVUELVE EL MENSAJE DE QUE SE HA BORRADO CORRECTAMENTE
          return $this->json('Deleted a project successfully with id ' . $id);
      }

}