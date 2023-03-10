<?php

    namespace App\Controller;

    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    class SesionController extends AbstractController
    {
        
        public function __construct(private ManagerRegistry $doctrine)
        {
        
        }

        //CIERRA SESION
        #[Route('/logout', name: 'app_logout', methods: 'GET')]
        public function logout(){
            throw new \Exception('Cierra Sesion');
        }

        /*
        //SE CAMBIA EL ROL DEL USUARIO ACTUAL A ADMIN
        #[Route('/setAdmin/{id}', name: 'app_setAdmin')]
        public function admin(int $id):Response
        {
            $entityManager=$this->doctrine->getManager();
            $user=$entityManager->getRepository(User::class)->find($id);

            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->render('landingPage.html.twig');
        }*/

    }