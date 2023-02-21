<?php
 
    namespace App\Controller;

    use App\Entity\Distribucion;
    use App\Entity\Mesa;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Component\HttpFoundation\Request;

#[Route(path:'/api', name:'api_')]
class apiDistribucion extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine)
    {
        
    }

    //DEVUELVE UN JSON CON TODAS LAS DISTRIBUCIONES
    #[Route(path:'/distribucion', name:'distribucion', methods:'GET')]
    public function mesasFecha(): Response
    {
        //BUSCA TODAS LAS DISTRIBUCIONES DE LA BD
        $distribuciones = $this->doctrine
            ->getRepository(Distribucion::class)
            ->findAll();
 
        $data = [];
 
        //METE TODAS LAS DISTRIBUCIONES EN EL ARRAY DATA
        foreach ($distribuciones as $distribucion) {
           $data[] = [
                'id' => $distribucion->getId(),
                'fecha' => $distribucion->getFecha(),
                'x' => $distribucion->getX(),
                'y' => $distribucion->getY(),
                'mesa' => $distribucion->getMesa()->getId()
           ];
        }
 
        //DEVUELVE EL JSON CON LAS DISTRIBCIONES
        return $this->json($data);
    }

    
     //CREA UNA NUEVA DISPOSICION SEGUN LOS DATOS PASADOS POR POST
     #[Route(path:'/distribucion', name:"distribucion_new", methods:'POST')]
     public function new(Request $request): Response
     {
        
         $entityManager = $this->doctrine->getManager();
         
        //BUSCO LA MESA EN LA BD SEGUN SU ID
        $idMesa=$request->request->get('idmesa');

        $mesa = $this->doctrine
         ->getRepository(Mesa::class)
         ->find($idMesa);
 
         //CREO LA NUEVA DISTRIBUCION CON LAS PROPIEDADES 
         $distribucion = new Distribucion();
         $distribucion->setFecha($request->request->get('fecha'));
         $distribucion->setX($request->request->get('x'));
         $distribucion->setY($request->request->get('y'));
         $distribucion->setMesa($mesa);
            
         //LA GUARDO EN LA BASE DE DATOS
         $entityManager->persist($distribucion);
         $entityManager->flush();

        return $this->json('Created new project successfully with id ' . $distribucion->getId());
     }

     #[Route(path:'/distribucion/{id}', name:"distribucion_edit", methods:'PUT')]
     public function edit(Request $request, int $id): Response
     {
         $entityManager = $this->doctrine->getManager();
         //BUSCA LA MESA EN LA BASE DE DATOS
         $distribucion = $entityManager->getRepository(Distribucion::class)->find($id);
  
         //SI NO ENCUENTRA LA MESA DEVUELVE EL MENSAJE DE ERROR
         if (!$distribucion) {
             return $this->json('No project found for id ' . $id, 404);
         }

        //BUSCO LA MESA EN LA BD SEGUN SU ID
        $idMesa=$request->request->get('idmesa');

        $mesa = $this->doctrine
         ->getRepository(Mesa::class)
         ->find($idMesa);
  

        //CREO LA NUEVA DISTRIBUCION CON LAS PROPIEDADES
        $distribucion = new Distribucion();
        $distribucion->setFecha($request->request->get('fecha'));
        $distribucion->setX($request->request->get('x'));
        $distribucion->setY($request->request->get('y'));
        $distribucion->setMesa($mesa);
      
        $entityManager->flush();
  
        //CREA EL ARRAY CON LOS DATOS DE LA MESA
        $data =  [
            'id' => $distribucion->getId(),
            'fecha' => $distribucion->getFecha(),
            'x' => $distribucion->getX(),
            'y' => $distribucion->getY(),
            'mesa' => $distribucion->getMesa()->getId()
        ];
          
        //DEVUELVE EL JSON CON LOS DATOS DE ESA MESA
        return $this->json($data);
    }

    //BORRA UNA MESA DE LA BASE DE DATOS SEGUN SU ID
    #[Route(path:'/distribucion/{id}', name:"distribucion_delete", methods:'DELETE')]
    public function delete(int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        //BUSCA LA MESA EN LA BASE DE DATOS
        $distribucion = $entityManager->getRepository(Distribucion::class)->find($id);
 
        //SI NO ENCUENTRA LA MESA DEVUELVE EL MENSAJE DE ERROR
        if (!$distribucion) {
            return $this->json('No project found for id' . $id, 404);
        }
 
        //BORRA LA MESA DE LA BASE DE DATOS
        $entityManager->remove($distribucion);
        $entityManager->flush();
 
        //UNA VEZ BORRADA DEVUELVE EL MENSAJE DE Q SE HA BORRADO CORRECTAMENTE
        return $this->json('Deleted a project successfully with id ' . $id);
    }
  
}