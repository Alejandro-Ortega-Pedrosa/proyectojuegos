<?php
 
    namespace App\Controller;

    use App\Entity\Juego;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\Persistence\ManagerRegistry;

#[Route(path:'/api', name:'api_')]
class apiJuego extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine)
    {
        
    }

    //DEVUELVE UN JSON CON TODAS LAS MESAS DE LA BD
    #[Route(path:'/juego', name:'juego_index', methods:'GET')]
    public function index(): Response
    {
        //BUSCA TODAS LAS MESAS DE LA BD
        $juegos = $this->doctrine
            ->getRepository(Juego::class)
            ->findAll();
 
        $data = [];
 
        //METE TODAS LAS MESAS EN EL ARRAY DATA
        foreach ($juegos as $juego) {
           $data[] = [
                'id' => $juego->getId(),
                'nombre' => $juego->getNombre(),
                'width' => $juego->getWidth(),
                'height' => $juego->getHeight()
           ];
        }
 
        //DEVUELVE EL JSON CON LAS MESAS
        return $this->json($data);
    }
}