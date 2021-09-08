<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHash): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }

        $agence = new Agence();

        $form = $this->createForm(AgenceType::class, $agence);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $agence = $form->getData();

            $agence->setPassword($passwordHash->hashPassword($agence, $agence->getPassword()));
            $entityManager->persist($agence);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
