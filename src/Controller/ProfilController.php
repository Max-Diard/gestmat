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

class ProfilController extends AbstractController
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[
        Route('/profil', name: 'profil')
    ]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[
        Route('/profil/add', name: 'profil_add'),
        IsGranted('ROLE_USER')
    ]
    public function addAdhrent(Request $request): Response
    {

        $adhrent = new Adherent();

        $form = $this->createForm(AdherentType::class, $adhrent);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adherent = $form->getData();
            
            $this->entityManager->persist($adhrent);
            $this->entityManager->flush();
        }

        return $this->render('profil/addAdherent.html.twig', [
            'formAdherent' => $form->createView()
        ]);
    }
}
