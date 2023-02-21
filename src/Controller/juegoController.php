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


    class juegoController extends AbstractController{

        public function __construct(private ManagerRegistry $doctrine)
        {
        
        }
       
        //FORMULARIO PARA CREAR UN JUEGO NUEVO
        #[Route('/gestionJuegosForm', name: 'gestionJuegosForm')] 
        public function gestionJuegosForm(Request $request,  EntityManagerInterface $em, SluggerInterface $slugger):Response{

            //FORMULARIO
            $juego = new Juego();
    
            $form = $this->createForm(JuegoType::class, $juego);
    
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $juego = $form->getData();

                $foto = $form->get('foto')->getData();

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


                $em->persist($juego);
                $em->flush();
            }

        
            return $this->render('gestionJuegosForm.html.twig',['form' => $form]);

        }


        #[Route('/gestionJuegosEditForm/{id}', name: 'gestionJuegosEditForm')] 
        public function gestionJuegosEditForm(int $id, Request $request, SluggerInterface $slugger, EntityManagerInterface $em):Response{

            $juego = $this->doctrine
            ->getRepository(Juego::class)
            ->find($id);

            $form=$this->createForm(JuegoType::class, $juego, ['method' => 'POST']);

            $form->handleRequest($request);

            //SI EL FORMULARIO ESTÁ ENVIADO
            if ($form->isSubmitted() && $form->isValid()) {

                $juego = $form->getData();

                $foto = $form->get('foto')->getData();

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


                $em->persist($juego);
                $em->flush();

                return $this->redirect($this->generateUrl('gestionJuegosTabla'));
            }


 
            //DEVUELVE EL FORMULARIO RELLENO
            return $this->render('gestionJuegosEditForm.html.twig', ['form' => $form, 'juego' => $juego]);

        }

        #[Route('/gestionJuegosDelete/{id}', name: 'gestionJuegosDelete')] 
        public function gestionJuegosDelete(int $id):Response{

            $entityManager = $this->doctrine->getManager();
            //BUSCA LA MESA EN LA BASE DE DATOS
            $juego = $entityManager->getRepository(Juego::class)->find($id);
    
            //BORRA LA MESA DE LA BASE DE DATOS
            $entityManager->remove($juego);
            $entityManager->flush();
 
            //DEVUELVE EL FORMULARIO RELLENO
            return $this->redirect($this->generateUrl('gestionJuegosTabla'));

        }

        //TABLA DE JUEGOS
        #[Route('/gestionJuegosTabla', name: 'gestionJuegosTabla')] 
        public function gestionJuegosTabla(Request $request, PaginatorInterface $paginator):Response{
    
            //BUSCA TODAS LAS MESAS DE LA BD
            $juegos = $this->doctrine
                ->getRepository(Juego::class)
                ->findAll();
               
            // Paginate the results of the query
            $juegos = $paginator->paginate(
            // Doctrine Query, not results
            $juegos,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
            );
                   
            // Render the twig view
            return $this->render('gestionJuegosTabla.html.twig', [
                'juegos' => $juegos
            ]);
       
        }
       
    }