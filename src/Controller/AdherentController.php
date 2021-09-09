<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdherentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    //Liste de tous les adhérents
    #[
        Route('/adherent', name: 'adherent_all'),
        IsGranted('ROLE_USER')
    ]
    public function index(Request $request): Response
    {

        $allAdherent = $this->entityManager->getRepository(Adherent::class)->findAll();

        return $this->render('adherent/index.html.twig', [
            'allAdherent' => $allAdherent
        ]);
    }

    //Information de l'adhérents sélectionné
    #[
        Route('/adherent/profil/{id}', name: 'adherent_single')
    ]
    public function allAdherent(Adherent $adherent, Request $request): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();
            
            $this->entityManager->persist($adherent);
            $this->entityManager->flush();
        }

        return $this->render('adherent/singleAdherent.html.twig', [
            'adherentProfile' => $adherent,
            'formAdherent' => $form->createView()
        ]);
    }

    //Page pour ajouter un adhérent
    #[
        Route('/adherent/add', name: 'adherent_add'),
        IsGranted('ROLE_USER')
    ]
    public function addAdherent(Request $request): Response
    {

        $adherent = new Adherent();

        $form = $this->createForm(AdherentType::class, $adherent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();

            $agence = $this->getUser();

            $adherent->setAgence($agence);
            
            $this->entityManager->persist($adherent);
            $this->entityManager->flush();

            $this->addFlash(
                'successNewAdherent',
                'Félicitations, vous avez créer un nouvel adhérent !'
            );
        }

        return $this->render('adherent/addAdherent.html.twig', [
            'formAdherent' => $form->createView()
        ]);
    }
}
