<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\ChangePasswordType;
use App\Form\ChangeProfileType;
use App\Form\UserAgenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'userProfile' => $user
        ]);
    }

    #[Route('/profile/change_password', name: 'user_password')]
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHash): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $user->setPassword($passwordHash->hashPassword($user, $user->getPassword()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('profile');
        }
        

        return $this->render('user/changePassword.html.twig', [
            'formPassword' => $form->createView()
        ]);
    }

    #[Route('/profile/change_profile', name: 'user_profile')]
    public function changeProfile(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangeProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render('user/changeProfile.html.twig', [
            'formChange' => $form->createView()
        ]);
    }
}
