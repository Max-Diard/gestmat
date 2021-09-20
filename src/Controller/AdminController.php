<?php

namespace App\Controller;

use App\Entity\AdherentOption;
use App\Entity\Agence;
use App\Entity\User;
use App\Form\AgenceType;
use App\Form\ChangeAgenceType;
use App\Form\DelegationAgenceType;
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

    //Page principal pour les admins
    #[
        Route('/admin', name: 'admin'),
        IsGranted("ROLE_ADMIN")
    ]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', []);
    }

    //----------------------------- Affichage Agence ---------------------------------//
    //Voir toute les agences
    #[
        Route('/admin/agence', name: 'admin_agence'),
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
        Route('/admin/agence/add', name: 'admin_agence_add'),
        IsGranted("ROLE_ADMIN")
    ]
    public function newAgence(Request $request): Response
    {
        $agence = new Agence();

        $form = $this->createForm(AgenceType::class, $agence);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $agence = $form->getData();

            $this->entityManager->persist($agence);
            $this->entityManager->flush();

            $picture = $form->get('link_picture')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($picture){
                $pictureExt = $picture->guessExtension();
                $pictureName = md5(uniqid()) . '.' . $pictureExt;
                $picture->move($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/', $pictureName);
                $agence->setLinkPicture($pictureName);
            }

            return $this->redirectToRoute('admin_agence');
        }

        return $this->render('admin/agence/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //Voir l'agence sélectionné
    #[
        Route('/admin/agence/{id}', name: 'admin_agence_single'),
        IsGranted("ROLE_ADMIN")
    ]
    public function singleAgence(Agence $agence, Request $request): Response
    {
        $allAgence = $this->entityManager->getRepository(Agence::class)->findAll();
        $agenceId = $agence->getId();
        for($i = 0; $i < count($allAgence); $i++){
            $otherAgences = $this->entityManager->getRepository(Agence::class)->findOtherAgence($agenceId);
        }

        $agenceGiveDroit = [];
        
        foreach($otherAgences as $otherAgence){
            for($j = 0; $j < count($allAgence); $j++){
                if($otherAgence->getDroitAgence()[$j] === $agence){
                    $agenceGiveDroit[] = $otherAgence;
                }
            }
        }

        $form = $this->createForm(DelegationAgenceType::class, $agence, [
            'agences' => $otherAgences
        ]);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $delegation = $form->get('droit_agence')->getData();
            
            foreach($delegation as $deleg){
                $agence->addDroitAgence($deleg);
            }

            $this->entityManager->persist($agence);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_agence_single', ['id' => $agence->getId()]);
        }

        return $this->render('admin/agence/single.html.twig', [
            'agence' => $agence,
            'form' => $form->createView(),
            'agenceGiveDroit' => $agenceGiveDroit
        ]);
    }

    //Modifier l'agence sélectionné
    #[
        Route('/admin/agence/{id}/modify', name: 'admin_agence_modify'),
        IsGranted("ROLE_ADMIN")
    ]
    public function modifyAgence(Agence $agence, Request $request): Response
    {
        $linkPic = $agence->getLinkPicture();

        $form = $this->createForm(ChangeAgenceType::class, $agence);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $agence = $form->getData();

            $picture = $form->get('link_picture')->getData();
            // Si une image est envoyé alors on ajoute l'information en BDD
            if($picture){
                if ($linkPic){
                    unlink($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/' . $linkPic);
                }
                $pictureExt = $picture->guessExtension();
                $pictureName = md5(uniqid()) . '.' . $pictureExt;
                $picture->move($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/', $pictureName);
                $agence->setLinkPicture($pictureName);
            } 
            // On récupére le nom de l'image déjà existant et on lui renvoi
            else {
                $agence->setLinkPicture($linkPic);
            } 

            $this->entityManager->persist($agence);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_agence_single', ['id' => $agence->getId()]);
        }

        return $this->render('admin/agence/agenceModify.html.twig', [
            'form' => $form->createView(),
            'agence' => $agence
        ]);
    }

    //Page pour demander si l'on est sûr que l'on veux supprimer l'agence
    #[
        Route('/admin/agence/{id}/ask_remove', name: 'admin_agence_ask_remove'),
        IsGranted("ROLE_ADMIN")
    ]
    public function askRemoveAgence(Agence $agence): Response
    {
        return $this->render('admin/agence/askRemove.html.twig', [
            'agence' => $agence
        ]);
    }

    //Route pour supprimer l'agence sélectionner
    #[
        Route('/admin/agence/{id}/remove', name: 'admin_agence_remove'),
        IsGranted("ROLE_ADMIN")
    ]
    public function removeAgence(Agence $agence): Response
    {
        $this->entityManager->remove($agence);
        $this->entityManager->flush();

        unlink($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/' . $agence->getLinkPicture());
        rmdir($this->getParameter('agence_directory') . 'agence' . $agence->getId() . '/picture/');
        rmdir($this->getParameter('agence_directory') . 'agence' . $agence->getId() );

        return $this->redirectToRoute('admin_agence');
    }

    //----------------------------- Affichage Utilisateurs ---------------------------------//
    //Voir tous les utilisateurs
    #[
        Route('admin/user', name: 'admin_user_all'),
        IsGranted("ROLE_ADMIN")
    ]
    public function allUser(): Response
    {
        $allUser = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/user/allUser.html.twig', [
            'allUser' => $allUser
        ]);
    }

    //Créer un utilisateur
    #[
        Route('/admin/user/new', name: 'admin_user_new'),
        IsGranted('ROLE_ADMIN')    
    ]
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

            return $this->redirectToRoute('admin_user_all');
        }

        return $this->render('admin/user/newUser.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //Ajouter une agence à un utilisateur
    #[
        Route('admin/user/{id}/add_agence', name: 'admin_user_add_agence'),
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

            return $this->redirectToRoute('admin_user_all');
        }

        return $this->render('admin/user/addAgence.html.twig', [
            'formAgence' => $form->createView()
        ]);
    }

    //Page pour demander si l'on est sur que l'on veux supprimer l'user sélectionné
    #[
        Route('admin/user/{id}/remove/ask', name: 'admin_user_ask_remove'),
        IsGranted("ROLE_ADMIN")
    ]
    public function askRemoveUser(User $user): Response
    {
        return $this->render('admin/user/askRemove.html.twig', [
            'user' => $user
        ]);
    }

    //Route pour supprimé l'user sélectionné
    #[
        Route('admin/user/{id}/remove', name: 'admin_user_remove'),
        IsGranted("ROLE_ADMIN")
    ]
    public function removeUser(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_user_all');
    }
}
