<?php
 
    namespace App\Controller\Api;

    use App\Entity\Mesa;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Component\HttpFoundation\Request;

#[Route(path:'/api', name:'api_')]
class apiMesa extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine)
    {
        
    }

    //DEVUELVE UN JSON CON TODAS LAS MESAS DE LA BD
    #[Route(path:'/mesas', name:'mesa_index', methods:'GET')]
    public function index(): Response
    {
        //BUSCA TODAS LAS MESAS DE LA BD
        $mesas = $this->doctrine
            ->getRepository(Mesa::class)
            ->findAll();
 
        $data = [];
 
        //METE TODAS LAS MESAS EN EL ARRAY DATA
        foreach ($mesas as $mesa) {
           $data[] = [
                'id' => $mesa->getId(),
                'width' => $mesa->getWidth(),
                'height' => $mesa->getHeight(),
                'x' => $mesa->getX(),
                'y' => $mesa->getY()
           ];
        }
 
        //DEVUELVE EL JSON CON LAS MESAS
        return $this->json($data);
    }

    
    //CREA UNA NUEVA MESA SEGUN LOS DATOS PASADOS POR POST
    #[Route(path:'/mesa', name:"mesa_new", methods:'POST')]
    public function new(Request $request): Response
    {
        $entityManager = $this->doctrine->getManager();

        //CREO LA NUEVA MESA CON LAS PROPIEDADES 
        $mesa = new Mesa();
        $mesa->setWidth($request->request->get('width'));
        $mesa->setHeight($request->request->get('height'));
        $mesa->setX($request->request->get('x'));
        $mesa->setY($request->request->get('y'));
               
        //LA GUARDO EN LA BASE DE DATOS
        $entityManager->persist($mesa);
        $entityManager->flush();
 
        return $this->json('Created new project successfully with id ' . $mesa->getId());
    }
 

    //DEVUELVE UN JSON CON LOS DATOS DE UNA MESA CUYO ID SE PASA POR LA URL
    #[Route(path:'/mesa/{id}', name:"mesa_show", methods:'GET')]
    public function show(int $id): Response
    {
        //BUSCA LA MESA SEGUN EL ID PASADO POR LA URL
        $mesa = $this->doctrine
            ->getRepository(Mesa::class)
            ->find($id);
 
        //SI NO ENCUENTRA LA MESA SALTA EL MENSAJE DE ERROR
        if (!$mesa) {
 
            return $this->json('No project found for id' . $id, 404);
        }
 
        //CREA EL ARRAY CON LOS DATOS DE LA MESA
        $data =  [
            'id' => $mesa->getId(),
            'width' => $mesa->getWidth(),
            'height' => $mesa->getHeight(),
            'x' => $mesa->getX(),
            'y' => $mesa->getY()
        ];
         
        //DEVUELVE EL JSON CON LOS DATOS DE ESA MESA
        return $this->json($data);
    }
 

    #[Route(path:'/mesa/{id}', name:"mesa_edit", methods:'PUT')]
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        //BUSCA LA MESA EN LA BASE DE DATOS
        $mesa = $entityManager->getRepository(Mesa::class)->find($id);
 
        //SI NO ENCUENTRA LA MESA DEVUELVE EL MENSAJE DE ERROR
        if (!$mesa) {
            return $this->json('No project found for id ' . $id, 404);
        }
 
         //CREO LA NUEVA MESA CON LAS PROPIEDADES 
         $mesa->setWidth($request->request->get('width'));
         $mesa->setHeight($request->request->get('height'));
         $mesa->setX($request->request->get('x'));
         $mesa->setY($request->request->get('y'));
     
        $entityManager->flush();
 
        //CREA EL ARRAY CON LOS DATOS DE LA MESA
        $data =  [
            'id' => $mesa->getId(),
            'width' => $mesa->getWidth(),
            'height' => $mesa->getHeight(),
            'x' => $mesa->getX(),
            'y' => $mesa->getY()
        ];
         
        //DEVUELVE EL JSON CON LOS DATOS DE ESA MESA
        return $this->json($data);
    }
 
   
    //BORRA UNA MESA DE LA BASE DE DATOS SEGUN SU ID
     #[Route(path:'/mesa/{id}', name:"mesa_delete", methods:'DELETE')]
    public function delete(int $id): Response
    {
        $entityManager = $this->doctrine->getManager();
        //BUSCA LA MESA EN LA BASE DE DATOS
        $mesa = $entityManager->getRepository(Mesa::class)->find($id);
 
        //SI NO ENCUENTRA LA MESA DEVUELVE EL MENSAJE DE ERROR
        if (!$mesa) {
            return $this->json('No project found for id' . $id, 404);
        }
 
        //BORRA LA MESA DE LA BASE DE DATOS
        $entityManager->remove($mesa);
        $entityManager->flush();
 
        //UNA VEZ BORRADA DEVUELVE EL MENSAJE DE Q SE HA BORRADO CORRECTAMENTE
        return $this->json('Deleted a project successfully with id ' . $id);
    }
 
 
}
