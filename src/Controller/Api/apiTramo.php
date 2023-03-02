<?php
 
    namespace App\Controller\Api;

    use App\Entity\Tramo;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry;

#[Route(path:'/api', name:'api_')]
class apiTramo extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine)
    {
        
    }

    //DEVUELVE UN JSON CON TODAS LOS TRAMOS DE LA BD
    #[Route(path:'/tramo', name:'tramo_index', methods:'GET')]
    public function index(): Response
    {
        //BUSCA TODAS LOS TRAMOS DE LA BD
        $tramos = $this->doctrine
            ->getRepository(Tramo::class)
            ->findAll();
 
        $data = [];
 
        //METE TODOS LOS TRAMOS EN EL ARRAY DATA
        foreach ($tramos as $tramo) {
           $data[] = [
                'id' => $tramo->getId(),
                'hora' => $tramo->getHora()
           ];
        }
 
        //DEVUELVE EL JSON CON LOS TRAMOS
        return $this->json($data);
    }
}