<?php

namespace App\Controller;

use App\Entity\AdherentOption;
use App\Entity\Agence;
use App\Entity\User;
use App\Form\AgenceType;
use App\Form\OptionType;
use App\Form\UserAgenceType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[
        Route('/admin', name: 'admin'),
        IsGranted("ROLE_ADMIN")
    ]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
        ]);
    }

    //Voir toute les agences
    #[
        Route('/admin/agence', name: 'agence'),
        IsGranted("ROLE_ADMIN")
    ]
    public function allAgence(): Response
    {
        $agence = $this->entityManager->getRepository(Agence::class)->findAll();

        return $this->render('admin/agence/index.html.twig', [
            'allAgence' => $agence
        ]);
    }

    // Créer une nouvelle agence
    #[
        Route('/admin/agence/add', name: 'agence_add'),
        IsGranted("ROLE_ADMIN")
    ]
    public function newAgence(Request $request): Response
    {
        $lastAgence = $this->entityManager->getRepository(Agence::class)->findByLastId();
        $lastAgence = $lastAgence[0]->getId();

        if ($lastAgence != ''){
            $lastAgence++;
        } else{
            $lastAgence = 1;
        }
        $agence = new Agence();

        $form = $this->createForm(AgenceType::class, $agence);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $agence = $form->getData();

            $picture = $form->get('link_picture')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($picture){
                $pictureExt = $picture->guessExtension();
                $pictureName = md5(uniqid()) . '.' . $pictureExt;
                $picture->move($this->getParameter('agence_directory') . 'agence' . $lastAgence . '/picture/', $pictureName);
                $agence->setLinkPicture($pictureName);
            }

            $this->entityManager->persist($agence);
            $this->entityManager->flush();

            return $this->redirectToRoute('agence');
        }

        return $this->render('admin/agence/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //Voir l'agence sélectionné
    #[
        Route('/admin/agence/{id}', name: 'agence_single'),
        IsGranted("ROLE_ADMIN")
    ]
    public function singleAgence(Agence $agence, Request $request): Response
    {
        return $this->render('admin/agence/single.html.twig', [
            'agence' => $agence
        ]);
    }

    //Voir tous les utilisateurs
    #[
        Route('admin/user', name: 'user_all'),
        IsGranted("ROLE_ADMIN")
    ]
    public function allUser(): Response
    {
        $allUser = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/user/allUser.html.twig', [
            'allUser' => $allUser
        ]);
    }

    //Ajouter une agence à un utilisateur
    #[
        Route('admin/user/{id}/add_agence', name: 'user_add_agence'),
        IsGranted("ROLE_ADMIN")
    ]
    public function addAgence(User $user, Request $request): Response
    {
        $form = $this->createForm(UserAgenceType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('user_all');
        }

        return $this->render('admin/user/addAgence.html.twig', [
            'formAgence' => $form->createView()
        ]);
    }

    //Ajouter un utilisateur
    #[Route('/admin/user/new', name: 'admin_user_new')]
    public function newUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHash): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $user->setPassword($passwordHash->hashPassword($user, $user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_all');
        }

        return $this->render('admin/user/newUser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
