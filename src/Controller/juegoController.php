<?php
    namespace App\Controller;

    use App\Entity\Juego;
    use App\Form\JuegoType;
    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\String\Slugger\SluggerInterface;
    use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

    class juegoController extends AbstractController{

        public function __construct(private ManagerRegistry $doctrine)
        {
        
        }
       
        //FORMULARIO PARA CREAR UN JUEGO NUEVO
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionJuegosForm', name: 'gestionJuegosForm')] 
        public function gestionJuegosForm(Request $request,  EntityManagerInterface $em, SluggerInterface $slugger):Response{

            //SE CREA EL NUEVO JUEGO
            $juego = new Juego();
    
            //SE CREA EL FORM
            $form = $this->createForm(JuegoType::class, $juego);
    
            $form->handleRequest($request);

            //CUANDO EL FORMULARIO ESTA ENVIADO
            if ($form->isSubmitted() && $form->isValid()) {

                $juego = $form->getData();

                $foto = $form->get('foto')->getData();

                //SI TIENE FOTO GUARDA EL NOMBRE DEL ARCHIVO
                if ($foto) {
                    
                    $originalFilename = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                    
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$foto->guessExtension();
    
                    // Move the file to the directory where brochures are stored
                    $foto->move(
                        $this->getParameter('foto_directorio_juego'),
                        $newFilename
                    );

                    $juego->setFoto($newFilename);
                }

                //GUARDA EL JUEGO EN LA BD
                $em->persist($juego);
                $em->flush();
                
            }

            //DEVUELVE LA PLANTILLA CON EL FORMULARIO
            return $this->render('gestionJuegosForm.html.twig',['form' => $form]);

        }

        //FORMULARIO PARA EDITAR UN JUEGO SEGUN SU ID
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionJuegosEditForm/{id}', name: 'gestionJuegosEditForm')] 
        public function gestionJuegosEditForm(int $id, Request $request, SluggerInterface $slugger, EntityManagerInterface $em):Response{

            //BUSCA EL JUEGO POR SU ID EN LA BD
            $juego = $this->doctrine
            ->getRepository(Juego::class)
            ->find($id);

            //CREA EL FORM
            $form=$this->createForm(JuegoType::class, $juego, ['method' => 'POST']);

            $form->handleRequest($request);

            //SI EL FORMULARIO ESTÁ ENVIADO
            if ($form->isSubmitted() && $form->isValid()) {

                $juego = $form->getData();

                $foto = $form->get('foto')->getData();

                //SI TIENE FOTO SE GUARDA EL NOMBRE DEL ARCHIVO
                if ($foto) {
                    
                    $originalFilename = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                    
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$foto->guessExtension();
    
                    // Move the file to the directory where brochures are stored
                    $foto->move(
                        $this->getParameter('foto_directorio_juego'),
                        $newFilename
                    );

                    $juego->setFoto($newFilename);
                }

                //SE GUARDA EL JUEGO EN LA BD
                $em->persist($juego);
                $em->flush();

                //SE PINTA LA TABLA DE JUEGOS
                return $this->redirect($this->generateUrl('gestionJuegosTabla'));
            }


 
            //DEVUELVE LA PLANTILLA CON EL FORMULARIO RELLENO
            return $this->render('gestionJuegosEditForm.html.twig', ['form' => $form, 'juego' => $juego]);

        }

        //SE BORRA UN JUEGO SEGUN SU ID
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionJuegosDelete/{id}', name: 'gestionJuegosDelete')] 
        public function gestionJuegosDelete(int $id):Response{

            $entityManager = $this->doctrine->getManager();
            //BUSCA EL JUEGO EN LA BASE DE DATOS
            $juego = $entityManager->getRepository(Juego::class)->find($id);
    
            //BORRA EL JUEGO DE LA BASE DE DATOS
            $entityManager->remove($juego);
            $entityManager->flush();
 
            //DEVUELVE LA PLANTILLA CON EL FORMULARIO RELLENO
            return $this->redirect($this->generateUrl('gestionJuegosTabla'));

        }

        //TABLA DE JUEGOS
        #[IsGranted('ROLE_ADMIN')] 
        #[Route('/gestionJuegosTabla', name: 'gestionJuegosTabla')] 
        public function gestionJuegosTabla(Request $request, PaginatorInterface $paginator):Response{
    
            //BUSCA TODAS LAS MESAS DE LA BD
            $juegos = $this->doctrine
                ->getRepository(Juego::class)
                ->findAll();
               
            //PAGINA LOS RESULTADOS DE LA RESPUESTA
            $juegos = $paginator->paginate(
            $juegos,
            //DEFINE LOS PARAMETROS
            $request->query->getInt('page', 1),
            //JUEGOS POR PAGINA
            5
            );
                   
            // Render the twig view
            return $this->render('gestionJuegosTabla.html.twig', [
                'juegos' => $juegos
            ]);
       
        }
       
    }