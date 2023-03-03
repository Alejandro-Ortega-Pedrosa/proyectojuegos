<?php
 
    namespace App\Controller\Api;

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
    #[Route(path:'/distribucion/{fecha}', name:'distribucion', methods:'GET')]
    public function mesasFecha(string $fecha): Response
    {
        //BUSCA TODAS LAS DISTRIBUCIONES DE LA BD
        $distribuciones = $this->doctrine
            ->getRepository(Distribucion::class)
            ->findBy(array('fecha'=>$fecha));
 
        $data = [];
 
        //METE TODAS LAS DISTRIBUCIONES EN EL ARRAY DATA
        foreach ($distribuciones as $distribucion) {
           $data[] = [
                'id' => $distribucion->getId(),
                'fecha' => $distribucion->getFecha(),
                'x' => $distribucion->getX(),
                'y' => $distribucion->getY(),
                'mesa' => $distribucion->getMesa()->getId(),
                'height' => $distribucion->getMesa()->getHeight(),
                'width' => $distribucion->getMesa()->getWidth()
           ];
        }
 
        //DEVUELVE EL JSON CON LAS DISTRIBCIONES
        return $this->json($data);
    }

    
     //CREA UNA NUEVA DISTRIBUCION SEGUN LOS DATOS PASADOS POR POST
     #[Route(path:'/distribucion', name:"distribucion_new", methods:'POST')]
     public function new(Request $request): Response
     {
        
         $entityManager = $this->doctrine->getManager();
         
        //BUSCO LA MESA EN LA BD SEGUN SU ID
        $mesa = $this->doctrine
         ->getRepository(Mesa::class)
         ->find($request->request->get('idmesa'));
 
         //CREO LA NUEVA DISTRIBUCION CON LAS PROPIEDADES 
         $distribucion = new Distribucion();
         $distribucion->setFecha($request->request->get('fecha'));
         $distribucion->setX($request->request->get('x'));
         $distribucion->setY($request->request->get('y'));
         $distribucion->setMesa($mesa);
            
         //LA GUARDO EN LA BASE DE DATOS
         $entityManager->persist($distribucion);
         $entityManager->flush();

        return $this->json('Creada con id ' . $distribucion->getId());
     }

     #[Route(path:'/distribucion/{id}', name:"distribucion_edit", methods:'PUT')]
     public function edit(Request $request, int $id): Response
     {
         $entityManager = $this->doctrine->getManager();
         
         //BUSCA LA DISTRIBUCION EN LA BASE DE DATOS
         $distribucion = $entityManager->getRepository(Distribucion::class)->find($id);
  
         //SI NO ENCUENTRA LA DITRIBUCION DEVUELVE EL MENSAJE DE ERROR
         if (!$distribucion) {
             return $this->json('No encontrada con id ' . $id, 404);
         }

        //BUSCO LA MESA EN LA BD SEGUN SU ID
        $mesa = $this->doctrine
         ->getRepository(Mesa::class)
         ->find($request->request->get('idmesa'));
  

        //CREO LA NUEVA DISTRIBUCION CON LAS PROPIEDADES
        $distribucion->setFecha($request->request->get('fecha'));
        $distribucion->setX($request->request->get('x'));
        $distribucion->setY($request->request->get('y'));
        $distribucion->setMesa($mesa);
      
        $entityManager->persist($distribucion);
        $entityManager->flush();
  
        //CREA EL ARRAY CON LOS DATOS DE LA DISTRIBUCION
        $data =  [
            'id' => $distribucion->getId(),
            'fecha' => $distribucion->getFecha(),
            'x' => $distribucion->getX(),
            'y' => $distribucion->getY(),
            'mesa' => $distribucion->getMesa()->getId(),
        ];
          
        //DEVUELVE EL JSON CON LOS DATOS DE ESA DISTRIBUCION
        return $this->json($data);
    }

    //BORRA UNA DISTRIBUCION DE LA BASE DE DATOS SEGUN SU ID
    #[Route(path:'/distribucion/{id}', name:"distribucion_delete", methods:'DELETE')]
    public function delete(int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        //BUSCA LA DISTRIBUCION EN LA BASE DE DATOS
        $distribucion = $entityManager->getRepository(Distribucion::class)->find($id);
 
        //SI NO ENCUENTRA LA DISTRIBUCION DEVUELVE EL MENSAJE DE ERROR
        if (!$distribucion) {
            return $this->json('No encontrada con id' . $id, 404);
        }
 
        //BORRA LA DISTRIBUCION DE LA BASE DE DATOS
        $entityManager->remove($distribucion);
        $entityManager->flush();
 
        //UNA VEZ BORRADA DEVUELVE EL MENSAJE DE Q SE HA BORRADO CORRECTAMENTE
        return $this->json('Se ha borrado con id ' . $id);
    }
  
}