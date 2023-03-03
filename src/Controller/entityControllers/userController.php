<?php
    namespace App\Controller\entityControllers;

    use App\Form\UserType;
    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\Persistence\ManagerRegistry;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;


    class userController extends AbstractController{

        public function __construct(private ManagerRegistry $doctrine)
        {
        
        }

        //FORMULARIO PARA EDITAR LAS PROPIEDADES DEL USUARIO ACTUAL
        #[Route('/userEditForm', name: 'userEditForm')] 
        public function gestionJuegosEditForm(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em):Response{

            $user = $this->getUser();

            $form=$this->createForm(UserType::class, $user, ['method' => 'POST']);

            $form->handleRequest($request);

            //SI EL FORMULARIO ESTÁ ENVIADO
            if ($form->isSubmitted() && $form->isValid()) {
                //CODIFICA LA CONTRASEÑA
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $user = $form->getData();

                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('index'));
            }

            //DEVUELVE EL FORMULARIO RELLENO
            return $this->render('userEditForm.html.twig', ['form' => $form, 'user' => $user]);

        }
    }