<?php

namespace App\Controller;

use App\Entity\Juego;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\mail;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    private mail $mail;

    public function __construct(private ManagerRegistry $doctrine,  mail $mail)
    {
        $this->mail =$mail;
    }

    #[Route('/', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //CODIFICA LA CONTRASEÑA
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $correo=$user->getUserIdentifier();

            $this->mail->sendEmail($correo);

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('index');
        }

        //BUSCA TODOS LOS JUEGOS DE LA BD
         $juegos = $this->doctrine
         ->getRepository(Juego::class)
         ->findBy(array(),array(),4);

        return $this->render('landingPage.html.twig', [
            'registrationForm' => $form->createView(),
            'juegos' => $juegos,
        ]);
    }
}
