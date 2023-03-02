<?php
 
    namespace App\Controller\Api;

    use App\Entity\Evento;
    use App\Entity\Invitacion;
    use App\Entity\User;
    use App\Service\BotTelegram;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Request;

#[Route(path:'/api', name:'api_')]
class apiInvitacion extends AbstractController
{
    private BotTelegram $botTelegram;

    public function __construct(private ManagerRegistry $doctrine, BotTelegram $botTelegram)
    {
        $this->botTelegram =$botTelegram;
    }

    //CREA UNA NUEVA INVITACION SEGUN LOS DATOS PASADOS POR POST
    #[Route(path:'/invitacion', name:"invitacion_new", methods:'POST')]
    public function new(Request $request): Response
    {
        $entityManager = $this->doctrine->getManager();

        //BUSCO EL USUARIO
        $user = $this->doctrine
        ->getRepository(User::class)
        ->find($request->request->get('user'));

        //BUSCO EL EVENTO
        $evento = $this->doctrine
        ->getRepository(Evento::class)
        ->find($request->request->get('evento'));

        //CREO LA NUEVA INVITACION CON LAS PROPIEDADES 
        $invitacion = new Invitacion();
        $invitacion->setUser($user);
        $invitacion->setEvento($evento);
        
        //LA GUARDO EN LA BASE DE DATOS
        $entityManager->persist($invitacion);
        $entityManager->flush();

        //MANDO LA INVITACION AL USUARIO
        $this->botTelegram->main($evento->getNombre());
 
        return $this->json('Created new project successfully with id ' . $invitacion->getId());
    }
}