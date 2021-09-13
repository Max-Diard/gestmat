<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    // #[Route('/register', name: 'register')]
    // public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHash): Response
    // {
    //     if($this->getUser()){
    //         return $this->redirectToRoute('adherent_all');
    //     }

    //     $user = new User();

    //     $form = $this->createForm(UserType::class, $user);

    //     $form->handleRequest($request);

    //     if($form->isSubmitted() && $form->isValid()){
    //         $user = $form->getData();

    //         $user->setPassword($passwordHash->hashPassword($user, $user->getPassword()));
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_login');
    //     }

    //     // $agence = new Agence();

    //     // $form = $this->createForm(AgenceType::class, $agence);

    //     // $form->handleRequest($request);

    //     // if($form->isSubmitted() && $form->isValid()){
    //     //     $agence = $form->getData();

    //     //     $agence->setPassword($passwordHash->hashPassword($agence, $agence->getPassword()));
    //     //     $entityManager->persist($agence);
    //     //     $entityManager->flush();

    //     //     return $this->redirectToRoute('app_login');
    //     // }

    //     return $this->render('register/index.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }
}
