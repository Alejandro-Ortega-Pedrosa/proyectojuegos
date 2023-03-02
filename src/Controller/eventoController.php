<?php
    namespace App\Controller;

    use App\Entity\Evento;
    use App\Entity\Invitacion;
    use App\Form\EventoType;
    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\String\Slugger\SluggerInterface;
    use Knp\Component\Pager\PaginatorInterface;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class eventoController extends AbstractController{

        public function __construct(private ManagerRegistry $doctrine)
        {
        
        }
       
        //FORMULARIO PARA CREAR UN EVENTO NUEVO
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionEventosForm', name: 'gestionEventosForm')] 
        public function gestionEventosForm(Request $request,  EntityManagerInterface $em):Response{

            $eventos = $this->doctrine
            ->getRepository(Evento::class)
            ->findAll();

             //SE CREA EL NUEVO EVENTO 
             $evento = new Evento();
    
             //SE CREA EL FORM
             $form = $this->createForm(EventoType::class, $evento);
     
             $form->handleRequest($request);
 
             //CUANDO EL FORMULARIO ESTA ENVIADO
             if ($form->isSubmitted() && $form->isValid()) {
 
                 $evento = $form->getData();    
 
                 $em->persist($evento);
                 $em->flush();
             }

             //DEVUELVE LA PLANTILLA CON EL FORMULARIO
            return $this->render('gestionEventosForm.html.twig', [
                'form' => $form
            ]);
           
        }

        //FORMULARIO PARA EDITAR UN EVENTO SEGUN SU ID
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionEventosEditForm/{id}', name: 'gestionEventosEditForm')] 
        public function gestionEventosEditForm(int $id, Request $request, SluggerInterface $slugger, EntityManagerInterface $em):Response{

            //BUSCA EL EVENTO EN LA BASE DE DATOS SEGUN SU ID
            $evento = $this->doctrine
            ->getRepository(Evento::class)
            ->find($id);

            //SE CREA EL FORM
            $form=$this->createForm(EventoType::class, $evento, ['method' => 'POST']);

            $form->handleRequest($request);

            //SI EL FORMULARIO ESTÁ ENVIADO
            if ($form->isSubmitted() && $form->isValid()) {

                $evento = $form->getData();

                $em->persist($evento);
                $em->flush();

                return $this->redirect($this->generateUrl('gestionEventosTabla'));
            }


            //DEVUELVE LA PLANTILLA CON EL FORMULARIO RELLENO
            return $this->render('gestionEventosEditForm.html.twig', ['form' => $form, 'evento' => $evento]);

        }

        //BORRA UN EVENTO SEGUN SU ID
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionEventosDelete/{id}', name: 'gestionEventosDelete')] 
        public function gestionEventosDelete(int $id):Response{

            $entityManager = $this->doctrine->getManager();
            //BUSCA EL EVENTO EN LA BASE DE DATOS
            $evento = $entityManager->getRepository(Evento::class)->find($id);
    
            //BORRA EL EVENTO DE LA BASE DE DATOS
            $entityManager->remove($evento);
            $entityManager->flush();
 
            //DEVUELVE LA TABLA DE LOS EVENTOS
            return $this->redirect($this->generateUrl('gestionEventosTabla'));

        }

        //TABLA DE EVENTOS
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionEventosTabla', name: 'gestionEventosTabla')] 
        public function gestionEventosTabla(Request $request, PaginatorInterface $paginator):Response{
    
            //BUSCA TODAS LOS EVENTOS DE LA BD
            $eventos = $this->doctrine
                ->getRepository(Evento::class)
                ->findAll();
               
            //PAGINA LOS RESULTADOS DE LA RESPUESTA
            $eventos = $paginator->paginate(
            $eventos,
            //DEFINE LOS PARAMETROS
            $request->query->getInt('page', 1),
            //EVENTOS POR PAGINA
            5
            );
                   
            //DEVUELVE LA PLANTILLA CON LA TABLA DE JUEGOS
            return $this->render('gestionEventosTabla.html.twig', [
                'eventos' => $eventos
            ]);
       
        }

        //PINTA UNA PLANTILLA PARA INVITACION A EVENTOS CON EL ID DEL EVENTO GUARDADA EN EL BOTON DE INVITAR
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionEventos/{id}', name: 'gestionEventos')] 
        public function gestionEventos(int $id):Response{


            return $this->render('gestionEventos.html.twig', ['id' => $id]);
           
        }

        //PINTA LOS EVENTOS DEL USUARIO ACTUAL 
        #[Route('/eventos', name: 'eventos')] 
        public function eventos():Response{

            //INVITACIONES DEL USUARIO 
            $invitaciones = $this->doctrine
            ->getRepository(Invitacion::class)
            ->findBy(array('user'=>$this->getUser()));

            //TODOS LOS EVENTOS
            $eventosBD = $this->doctrine
            ->getRepository(Evento::class)
            ->findAll();

            //ARRAY DE LOS EVENTOS
            $eventos=[];

            //COMPRUEBA QUE EL USUARIO ESTE INVITADO AL EVENTO
            foreach ($eventosBD as $evento) {
                foreach ($invitaciones as $invitacion) {
                    //SI EL ID DEL EVENTO ES EL MISMO QUE EL DE LA INVITACION SE GUARDA ESE EVENTO EN EL ARRAY
                    if($evento->getId()==$invitacion->getEvento()->getId()){
                        $eventos[]=$evento;
                        //SI ESE EVENTO YA ESTA NO VUELVE A MIRAR
                        break;
                    }
                }
            }

            //DEVUELVE LA PLANTILLA CON LOS EVENTOS DEL USUARIO
            return $this->render('eventos.html.twig', [
                'eventos' => $eventos,
            ]);
           
        }
       
    }